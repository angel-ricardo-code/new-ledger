<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function report(Request $request): Response
    {
        $month = $request->month ?? now()->format('Y-m');
        $format = $request->format ?? 'csv';
        $theme = in_array($request->theme, ['dark', 'light']) ? $request->theme : 'dark';

        [$year, $monthNum] = explode('-', $month);

        $transactions = Transaction::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $incomeTotal = (float) $transactions->where('type', 'income')->sum('amount');
        $expenseTotal = (float) $transactions->where('type', 'expense')->sum('amount');
        $reconTotal = (float) $transactions->where('type', 'reconciliation')->sum('amount');
        $balance = round($incomeTotal - $expenseTotal + $reconTotal, 2);

        $expenseCategories = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'name' => $first?->category?->name ?? 'Sin categoría',
                    'color' => $first?->category?->color_hex ?? '#6E6E73',
                    'total' => (float) $items->sum('amount'),
                ];
            })->sortByDesc('total')->values();

        $incomeCategories = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'name' => $first?->category?->name ?? 'Sin categoría',
                    'color' => $first?->category?->color_hex ?? '#6E6E73',
                    'total' => (float) $items->sum('amount'),
                ];
            })->sortByDesc('total')->values();

        $biggestExpense = $transactions->where('type', 'expense')->sortByDesc('amount')->first();
        $biggestIncome = $transactions->where('type', 'income')->sortByDesc('amount')->first();

        $daysInMonth = (int) now()->daysInMonth;
        $expenseDays = $transactions->where('type', 'expense')
            ->groupBy(fn($t) => $t->date->format('Y-m-d'))
            ->count();

        $avgDailyExpense = $daysInMonth > 0 ? round($expenseTotal / $daysInMonth, 2) : 0;

        $allExpenses = Transaction::where('user_id', auth()->id())->where('type', 'expense')->get();
        $totalExpenseAll = (float) $allExpenses->sum('amount');
        $firstTx = Transaction::where('user_id', auth()->id())->oldest('date')->first();
        $daysSinceFirst = $firstTx ? max(1, now()->diffInDays($firstTx->date)) : 1;
        $historicalAvgExpense = round($totalExpenseAll / $daysSinceFirst, 2);

        $overview = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $y = $d->year;
            $m = $d->month;
            $txs = Transaction::where('user_id', auth()->id())
                ->whereYear('date', $y)
                ->whereMonth('date', $m)
                ->get();
            $inc = (float) $txs->where('type', 'income')->sum('amount');
            $exp = (float) $txs->where('type', 'expense')->sum('amount');
            $rec = (float) $txs->where('type', 'reconciliation')->sum('amount');
            $overview[] = [
                'label' => $d->format('M Y'),
                'income' => $inc,
                'expense' => $exp,
                'balance' => round($inc - $exp + $rec, 2),
            ];
        }

        $allIncome = (float) Transaction::where('user_id', auth()->id())->where('type', 'income')->sum('amount');
        $allExpense = (float) Transaction::where('user_id', auth()->id())->where('type', 'expense')->sum('amount');
        $allRecon = (float) Transaction::where('user_id', auth()->id())->where('type', 'reconciliation')->sum('amount');
        $globalBalance = round($allIncome - $allExpense + $allRecon, 2);

        $totalMonths = $firstTx ? now()->diffInMonths($firstTx->date->startOfMonth()) + 1 : 1;
        $monthlyAvg = $totalMonths > 0 ? round($globalBalance / $totalMonths, 2) : 0;

        $lastRecon = Transaction::where('user_id', auth()->id())->where('type', 'reconciliation')
            ->latest('date')->first();

        $prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
        [$prevY, $prevM] = explode('-', $prevMonth);
        $prevTx = Transaction::where('user_id', auth()->id())
            ->whereYear('date', $prevY)->whereMonth('date', $prevM)->get();
        $prevInc = (float) $prevTx->where('type', 'income')->sum('amount');
        $prevExp = (float) $prevTx->where('type', 'expense')->sum('amount');
        $prevRec = (float) $prevTx->where('type', 'reconciliation')->sum('amount');
        $prevBalance = round($prevInc - $prevExp + $prevRec, 2);
        $vsPrevious = round($balance - $prevBalance, 2);

        $monthLabel = now()->create($month . '-01')->translatedFormat('F Y');

        if ($format === 'html') {
            return $this->renderHtml(
                $theme, $monthLabel, $transactions, $incomeTotal, $expenseTotal, $reconTotal, $balance,
                $incomeCategories, $expenseCategories, $overview, $biggestExpense, $biggestIncome,
                $avgDailyExpense, $historicalAvgExpense, $daysInMonth, $expenseDays,
                $globalBalance, $monthlyAvg, $totalMonths, $lastRecon, $vsPrevious
            );
        }

        return $this->renderCsv(
            $monthLabel, $transactions, $incomeTotal, $expenseTotal, $reconTotal, $balance,
            $incomeCategories, $expenseCategories, $overview, $biggestExpense, $biggestIncome,
            $avgDailyExpense, $historicalAvgExpense, $daysInMonth, $expenseDays,
            $globalBalance, $monthlyAvg, $totalMonths, $lastRecon, $vsPrevious
        );
    }

    private function formatNumber(float $value): string
    {
        return number_format($value, 2, '.', ',');
    }

    private function escapeCsv(string $value): string
    {
        return '"' . str_replace('"', '""', $value) . '"';
    }

    private function escHtml(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'income' => 'Ingreso',
            'expense' => 'Gasto',
            'reconciliation' => 'Reconciliación',
            default => $type,
        };
    }

    private function renderCsv(
        string $monthLabel,
        iterable $transactions,
        float $incomeTotal,
        float $expenseTotal,
        float $reconTotal,
        float $balance,
        iterable $incomeCategories,
        iterable $expenseCategories,
        array $overview,
        $biggestExpense,
        $biggestIncome,
        float $avgDailyExpense,
        float $historicalAvgExpense,
        int $daysInMonth,
        int $expenseDays,
        float $globalBalance,
        float $monthlyAvg,
        int $totalMonths,
        $lastRecon,
        float $vsPrevious
    ): Response {
        $bom = chr(239) . chr(187) . chr(191);
        $lines = [];
        $e = fn($v) => $this->escapeCsv($v);
        $n = fn($v) => $this->formatNumber($v);

        $lines[] = '========= LEDGER - BALANCE REPORT =========';
        $lines[] = 'Periodo,' . $e($monthLabel);
        $lines[] = 'Generado,' . $e(now()->format('Y-m-d H:i'));
        $lines[] = '';
        $lines[] = '=== RESUMEN ===';
        $lines[] = 'Concepto,Monto';
        $lines[] = 'Ingresos,' . $n($incomeTotal);
        $lines[] = 'Gastos,' . $n($expenseTotal);
        $lines[] = 'Reconciliaciones,' . $n($reconTotal);
        $lines[] = 'Balance,' . $n($balance);
        $lines[] = 'vs mes anterior,' . ($vsPrevious >= 0 ? '+' : '') . $n($vsPrevious);
        $lines[] = '';
        $lines[] = '=== INGRESOS POR CATEGORIA ===';
        $lines[] = 'Nombre,Monto,Porcentaje';
        foreach ($incomeCategories as $cat) {
            $pct = $incomeTotal > 0 ? round($cat['total'] / $incomeTotal * 100, 1) : 0;
            $lines[] = $e($cat['name']) . ',' . $n($cat['total']) . ',' . $n($pct) . '%';
        }
        $lines[] = '';
        $lines[] = '=== GASTOS POR CATEGORIA ===';
        $lines[] = 'Nombre,Monto,Porcentaje';
        foreach ($expenseCategories as $cat) {
            $pct = $expenseTotal > 0 ? round($cat['total'] / $expenseTotal * 100, 1) : 0;
            $lines[] = $e($cat['name']) . ',' . $n($cat['total']) . ',' . $n($pct) . '%';
        }
        $lines[] = '';
        $lines[] = '=== COMPARATIVA MENSUAL (6 meses) ===';
        $lines[] = 'Mes,Ingresos,Gastos,Balance';
        foreach ($overview as $o) {
            $lines[] = $e($o['label']) . ',' . $n($o['income']) . ',' . $n($o['expense']) . ',' . $n($o['balance']);
        }
        $lines[] = '';
        $lines[] = '=== TRANSACCIONES ===';
        $lines[] = 'Fecha,Tipo,Monto,Categoria,Nota';
        foreach ($transactions as $t) {
            $lines[] = implode(',', [
                $t->date->format('Y-m-d'),
                $this->typeLabel($t->type),
                $n((float) $t->amount),
                $e($t->category?->name ?? ''),
                $e($t->note ?? ''),
            ]);
        }
        $lines[] = '';
        $lines[] = '=== METRICAS GLOBALES ===';
        $lines[] = 'Balance global,' . $n($globalBalance);
        $lines[] = 'Promedio mensual,' . $n($monthlyAvg);
        $lines[] = 'Total meses,' . $totalMonths;
        if ($biggestExpense) {
            $lines[] = 'Mayor gasto,' . $n((float) $biggestExpense->amount) . ' (' . $e($biggestExpense->category?->name ?? 'Sin categoría') . ' - ' . $biggestExpense->date->format('Y-m-d') . ')';
        }
        if ($biggestIncome) {
            $lines[] = 'Mayor ingreso,' . $n((float) $biggestIncome->amount) . ' (' . $e($biggestIncome->category?->name ?? 'Sin categoría') . ' - ' . $biggestIncome->date->format('Y-m-d') . ')';
        }
        $lines[] = 'Dias sin gastos,' . max(0, $daysInMonth - $expenseDays);
        if ($lastRecon) {
            $lines[] = 'Ultima reconciliacion,' . $lastRecon->date->format('Y-m-d');
        }
        $lines[] = '';
        $lines[] = '=== INDICADORES ===';
        $lines[] = 'Gasto diario promedio,' . $n($avgDailyExpense);
        $lines[] = 'Gasto diario historico,' . $n($historicalAvgExpense);
        if ($expenseCategories->isNotEmpty()) {
            $top = $expenseCategories->first();
            $lines[] = 'Categoria mas cara,' . $e($top['name']) . ' (' . $n($top['total']) . ')';
        }

        $csv = $bom . implode("\n", $lines) . "\n";
        $filename = 'ledger_balance_' . now()->format('Y-m') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function renderHtml(
        string $theme = 'dark',
        string $monthLabel,
        iterable $transactions,
        float $incomeTotal,
        float $expenseTotal,
        float $reconTotal,
        float $balance,
        iterable $incomeCategories,
        iterable $expenseCategories,
        array $overview,
        $biggestExpense,
        $biggestIncome,
        float $avgDailyExpense,
        float $historicalAvgExpense,
        int $daysInMonth,
        int $expenseDays,
        float $globalBalance,
        float $monthlyAvg,
        int $totalMonths,
        $lastRecon,
        float $vsPrevious
    ): Response {
        $e = fn($v) => $this->escHtml($v);
        $n = fn($v) => $this->formatNumber($v);
        $balanceClass = $balance >= 0 ? 'income' : 'expense';
        $vsClass = $vsPrevious >= 0 ? 'income' : 'expense';
        $vsSign = $vsPrevious >= 0 ? '+' : '';

        // Theme CSS variables
        if ($theme === 'light') {
            $themeRoot = <<<'CSS'
:root {
  --bg: #FFFFFF; --surface: #F2F2F7; --elevated: #E5E5EA;
  --text: #1C1C1E; --secondary: #8E8E93; --separator: #D1D1D6;
  --accent: #007AFF; --green: #34C759; --red: #FF3B30;
  --orange: #FF9500;
  --body-bg: #f5f5f7;
  --card-bg: linear-gradient(135deg, #ffffff 0%, #f5f5f7 50%, #e8e8ed 100%);
  --card-shadow: 0 20px 40px rgba(0,0,0,0.08);
  --card-glow: radial-gradient(circle, rgba(0,0,0,0.03) 0%, transparent 70%);
  --label-color: rgba(0,0,0,0.55);
  --lbl-color: rgba(0,0,0,0.5);
  --ac-divider-bg: rgba(0,0,0,0.08);
}
CSS;
        } else {
            $themeRoot = <<<'CSS'
:root {
  --bg: #000000; --surface: #1C1C1E; --elevated: #2C2C2E;
  --text: #FFFFFF; --secondary: #8E8E93; --separator: #38383A;
  --accent: #0A84FF; --green: #30D158; --red: #FF453A;
  --orange: #FF9F0A;
  --body-bg: #0a0a0c;
  --card-bg: linear-gradient(135deg, #1c1c1e 0%, #2c2c2e 50%, #3a3a3c 100%);
  --card-shadow: 0 20px 40px rgba(0,0,0,0.3);
  --card-glow: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
  --label-color: rgba(255,255,255,0.55);
  --lbl-color: rgba(255,255,255,0.5);
  --ac-divider-bg: rgba(255,255,255,0.12);
}
CSS;
        }

        $daysWithoutExpenses = $daysInMonth - $expenseDays;

        $incomePct = $incomeTotal > 0 && ($incomeTotal + $expenseTotal) > 0 ? round($incomeTotal / ($incomeTotal + $expenseTotal) * 100, 1) : 0;
        $expensePct = $expenseTotal > 0 && ($incomeTotal + $expenseTotal) > 0 ? round($expenseTotal / ($incomeTotal + $expenseTotal) * 100, 1) : 0;

        $catRows = '';
        $maxCat = $expenseCategories->first()['total'] ?? 1;
        foreach ($expenseCategories as $cat) {
            $pct = $expenseTotal > 0 ? round($cat['total'] / $expenseTotal * 100, 1) : 0;
            $barW = round($cat['total'] / $maxCat * 100, 1);
            $catRows .= <<<ROW
            <tr>
                <td><span class="cat-dot" style="background:{$cat['color']}"></span> {$e($cat['name'])}</td>
                <td class="expense">{$n($cat['total'])}</td>
                <td>{$n($pct)}%</td>
            </tr>
            <tr class="bar-row"><td colspan="3"><div class="cat-bar"><div class="cat-bar-fill" style="width:{$barW}%;background:{$cat['color']}"></div></div></td></tr>
ROW;
        }

        $incCatRows = '';
        $maxIncCat = $incomeCategories->first()['total'] ?? 1;
        foreach ($incomeCategories as $cat) {
            $pct = $incomeTotal > 0 ? round($cat['total'] / $incomeTotal * 100, 1) : 0;
            $barW = round($cat['total'] / $maxIncCat * 100, 1);
            $incCatRows .= <<<ROW
            <tr>
                <td><span class="cat-dot" style="background:{$cat['color']}"></span> {$e($cat['name'])}</td>
                <td class="income">{$n($cat['total'])}</td>
                <td>{$n($pct)}%</td>
            </tr>
            <tr class="bar-row"><td colspan="3"><div class="cat-bar"><div class="cat-bar-fill" style="width:{$barW}%;background:{$cat['color']}"></div></div></td></tr>
ROW;
        }

        $overviewRows = '';
        foreach ($overview as $o) {
            $bClass = $o['balance'] >= 0 ? 'income' : 'expense';
            $overviewRows .= <<<ROW
            <tr>
                <td>{$e($o['label'])}</td>
                <td class="income">{$n($o['income'])}</td>
                <td class="expense">{$n($o['expense'])}</td>
                <td class="{$bClass}">{$n($o['balance'])}</td>
            </tr>
ROW;
        }

        $txRows = '';
        foreach ($transactions as $t) {
            $tClass = $t->type === 'income' ? 'income' : 'expense';
            $sign = $t->type === 'income' ? '+' : '-';
            $txRows .= <<<ROW
            <tr class="{$tClass}">
                <td>{$t->date->format('Y-m-d')}</td>
                <td>{$e($this->typeLabel($t->type))}</td>
                <td class="{$tClass}">{$sign}{$n((float) $t->amount)}</td>
                <td>{$e($t->category?->name ?? '—')}</td>
                <td>{$e($t->note ?? '')}</td>
            </tr>
ROW;
        }

        $expensePctTotal = $expenseTotal > 0 ? round($expenseTotal / ($incomeTotal + $expenseTotal) * 100, 1) : 0;

        $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ledger - Balance {$e($monthLabel)}</title>
<style>
  {$themeRoot}
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: var(--body-bg); color: var(--text); padding: 32px 40px; max-width: 960px; margin: 0 auto;
    -webkit-font-smoothing: antialiased;
  }
  @media (max-width: 600px) { body { padding: 16px; } }

  .apple-card {
    background: var(--card-bg);
    border-radius: 24px; padding: 28px; margin-bottom: 24px;
    box-shadow: var(--card-shadow); position: relative; overflow: hidden;
  }
  .apple-card::after {
    content: ''; position: absolute; top: -40%; right: -20%; width: 280px; height: 280px;
    background: var(--card-glow);
  }
  .ac-label { font-size: 15px; color: var(--label-color); margin-bottom: 6px; position: relative; z-index: 1; }
  .ac-balance { font-size: 40px; font-weight: 700; letter-spacing: -1px; position: relative; z-index: 1; }
  .ac-row { display: flex; justify-content: space-between; margin-top: 20px; position: relative; z-index: 1; }
  .ac-stat { text-align: center; }
  .ac-stat .val { font-size: 18px; font-weight: 600; }
  .ac-stat .lbl { font-size: 11px; color: var(--lbl-color); margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
  .ac-divider { width: 1px; background: var(--ac-divider-bg); }

  .income { color: var(--green); }
  .expense { color: var(--red); }

  section { margin-bottom: 32px; }
  h2 {
    font-size: 22px; font-weight: 700; letter-spacing: -0.3px; margin-bottom: 16px; padding-bottom: 8px;
    border-bottom: 1px solid var(--separator);
  }
  h2 .sub { font-size: 14px; font-weight: 400; color: var(--secondary); letter-spacing: 0; }

  table { width: 100%; border-collapse: collapse; }
  th {
    text-align: left; font-size: 11px; color: var(--secondary);
    text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 8px;
    border-bottom: 1px solid var(--separator); font-weight: 600;
  }
  td { padding: 10px 8px; border-bottom: 1px solid var(--separator); font-size: 14px; }
  tr:last-child td { border-bottom: none; }
  th:last-child, td:last-child { text-align: right; }
  td:nth-child(2), th:nth-child(2) { text-align: right; }
  td:nth-child(3), th:nth-child(3) { text-align: right; }
  td:nth-child(4), th:nth-child(4) { text-align: right; }

  .cat-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; vertical-align: middle; margin-right: 6px; }
  .cat-bar { height: 4px; border-radius: 2px; background: var(--elevated); overflow: hidden; margin: 2px 0; }
  .cat-bar-fill { height: 100%; border-radius: 2px; }
  .bar-row td { padding: 0 0 8px 0; border-bottom: none; }

  .kpi-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 24px; }
  @media (max-width: 600px) { .kpi-grid { grid-template-columns: 1fr; } }
  .kpi-card {
    background: var(--surface); border-radius: 16px; padding: 16px;
    border: 1px solid var(--separator);
  }
  .kpi-card .kpi-val { font-size: 22px; font-weight: 700; margin-top: 4px; }
  .kpi-card .kpi-label { font-size: 12px; color: var(--secondary); text-transform: uppercase; letter-spacing: 0.3px; }

  .footer { text-align: center; color: var(--secondary); font-size: 12px; padding: 32px 0 16px; border-top: 1px solid var(--separator); margin-top: 40px; }

  @media print {
    body { background: white; color: black; padding: 0.5in; }
    .apple-card { background: #f5f5f7 !important; box-shadow: none; border: 1px solid #ddd; color: black; }
    .apple-card::after { display: none; }
    .ac-label { color: #666; }
    .ac-stat .lbl { color: #666; }
    .kpi-card { background: #f5f5f7; border-color: #ddd; }
    .ac-divider { background: #ddd; }
    th { color: #666; }
    .footer { color: #666; }
    section { page-break-inside: avoid; }
  }
</style>
</head>
<body>

<div class="apple-card">
  <div class="ac-label">Balance — {$e($monthLabel)}</div>
  <div class="ac-balance {$balanceClass}">{$n($balance)}</div>
  <div class="ac-row">
    <div class="ac-stat"><div class="val income">{$n($incomeTotal)}</div><div class="lbl">Ingresos</div></div>
    <div class="ac-divider"></div>
    <div class="ac-stat"><div class="val expense">{$n($expenseTotal)}</div><div class="lbl">Gastos</div></div>
    <div class="ac-divider"></div>
    <div class="ac-stat"><div class="val {$vsClass}">{$vsSign}{$n($vsPrevious)}</div><div class="lbl">vs mes ant.</div></div>
  </div>
</div>

<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-label">Gasto diario promedio</div>
    <div class="kpi-val" style="color:var(--orange)">{$n($avgDailyExpense)}</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Histórico diario</div>
    <div class="kpi-val" style="color:var(--accent)">{$n($historicalAvgExpense)}</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Días sin gastos</div>
    <div class="kpi-val" style="color:var(--secondary)">{$daysWithoutExpenses} / {$daysInMonth}</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Reconciliaciones</div>
    <div class="kpi-val" style="color:var(--accent)">{$n($reconTotal)}</div>
  </div>
</div>

HTML;

        if ($incomeCategories->isNotEmpty()) {
            $html .= <<<HTML
<section>
  <h2>Ingresos por Categoría</h2>
  <table>
    <tr><th>Nombre</th><th>Monto</th><th>%</th></tr>
    {$incCatRows}
  </table>
</section>
HTML;
        }

        if ($expenseCategories->isNotEmpty()) {
            $html .= <<<HTML
<section>
  <h2>Gastos por Categoría</h2>
  <table>
    <tr><th>Nombre</th><th>Monto</th><th>%</th></tr>
    {$catRows}
  </table>
</section>
HTML;
        }

        $html .= <<<HTML
<section>
  <h2>Comparativa Mensual <span class="sub">últimos 6 meses</span></h2>
  <table>
    <tr><th>Mes</th><th>Ingresos</th><th>Gastos</th><th>Balance</th></tr>
    {$overviewRows}
  </table>
</section>

<section>
  <h2>Transacciones <span class="sub">{$e($monthLabel)}</span></h2>
  <table>
    <tr><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Categoría</th><th>Nota</th></tr>
    {$txRows}
  </table>
</section>

<section>
  <h2>Métricas Globales</h2>
  <table>
    <tr><th>Métrica</th><th>Valor</th></tr>
    <tr><td>Balance global</td><td class="income">{$n($globalBalance)}</td></tr>
    <tr><td>Promedio mensual</td><td>{$n($monthlyAvg)}</td></tr>
    <tr><td>Total de meses</td><td>{$totalMonths}</td></tr>
HTML;

        if ($biggestExpense) {
            $html .= <<<HTML
    <tr><td>Mayor gasto</td><td class="expense">{$n((float) $biggestExpense->amount)} — {$e($biggestExpense->category?->name ?? 'Sin categoría')} <span class="sub">({$biggestExpense->date->format('Y-m-d')})</span></td></tr>
HTML;
        }
        if ($biggestIncome) {
            $html .= <<<HTML
    <tr><td>Mayor ingreso</td><td class="income">{$n((float) $biggestIncome->amount)} — {$e($biggestIncome->category?->name ?? 'Sin categoría')} <span class="sub">({$biggestIncome->date->format('Y-m-d')})</span></td></tr>
HTML;
        }
        if ($lastRecon) {
            $html .= <<<HTML
    <tr><td>Última reconciliación</td><td>{$lastRecon->date->format('Y-m-d')}</td></tr>
HTML;
        }
        $html .= <<<HTML
  </table>
</section>

<div class="footer">
  Generado por Ledger — {$e(now()->format('Y-m-d H:i'))}
</div>

</body>
</html>
HTML;

        $filename = 'ledger_balance_' . now()->format('Y-m') . '.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
