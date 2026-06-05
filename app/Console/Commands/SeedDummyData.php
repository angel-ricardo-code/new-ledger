<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;

class SeedDummyData extends Command
{
    protected $signature = 'ledger:seed-dummy {--months=24}';
    protected $description = 'Create dummy user with months of random transactions';

    private array $catIds = [];
    private array $catType = [];

    public function handle(): int
    {
        $totalMonths = (int) $this->option('months');

        // ── User ──
        $this->line('Creando usuario DUMMY...');
        $user = User::firstOrCreate(
            ['username' => 'DUMMY'],
            [
                'name' => 'Usuario Dummy',
                'email' => 'dummy@ledger.test',
                'password' => '123.Angel',
            ]
        );
        $this->info($user->wasRecentlyCreated ? '  ✓ Usuario creado' : '  ~ Usuario ya existe');

        // ── Categories ──
        $this->line('Creando categorías...');
        $this->ensureCategories();
        $this->info('  ✓ ' . count($this->catIds) . ' categorías listas');

        // ── Delete existing dummy transactions ──
        Transaction::where('user_id', $user->id)->delete();

        // ── Generate transactions ──
        $this->line('Generando transacciones...');
        $this->output->progressStart($totalMonths);

        $now = now()->startOfMonth();
        $batch = [];
        $totalTx = 0;
        $totalIncome = 0;
        $totalExpense = 0;

        for ($i = $totalMonths - 1; $i >= 0; $i--) {
            $monthDate = (clone $now)->subMonths($i);
            $year = (int) $monthDate->format('Y');
            $month = (int) $monthDate->format('m');
            $days = (int) $monthDate->format('t');
            $ym = $monthDate->format('Y-m');

            $seasonMul = $month === 1 ? 0.8 : 1.0;
            $decMul = $month === 12 ? 1.4 : 1.0;
            $summerMul = ($month >= 6 && $month <= 8) ? 1.2 : 1.0;

            // ── Income: salary ──
            $salary = 2800 + random_int(-200, 200);
            $batch[] = $this->tx($user->id, 'Salario', $ym, random_int(1, 7), $salary, 'Salario mensual');
            $totalIncome += $salary;

            // ── Income: freelance (60%) ──
            if (random_int(1, 100) <= 60) {
                $amt = random_int(20, 80) * 10;
                $batch[] = $this->tx($user->id, 'Freelance', $ym, random_int(8, $days), $amt, 'Proyecto freelance');
                $totalIncome += $amt;
            }

            // ── Rent ──
            $amt = 800 + random_int(-50, 50);
            $batch[] = $this->tx($user->id, 'Renta', $ym, random_int(1, 5), $amt, 'Pago de renta');
            $totalExpense += $amt;

            // ── Food ──
            foreach ($this->randDays($days, random_int(8, 15)) as $d) {
                $amt = round(random_int(10, 80) * $seasonMul, 2);
                $note = ['Súper', 'Mercado', 'Comida rápida', 'Restaurante', 'Cafetería'][array_rand(['Súper', 'Mercado', 'Comida rápida', 'Restaurante', 'Cafetería'])];
                $batch[] = $this->tx($user->id, 'Alimentación', $ym, $d, $amt, $note);
                $totalExpense += $amt;
            }

            // ── Transport ──
            foreach ($this->randDays($days, random_int(3, 8)) as $d) {
                $amt = round(random_int(5, 50) * $seasonMul, 2);
                $note = ['Uber', 'Taxi', 'Gasolina', 'Metro', 'Camión'][array_rand(['Uber', 'Taxi', 'Gasolina', 'Metro', 'Camión'])];
                $batch[] = $this->tx($user->id, 'Transporte', $ym, $d, $amt, $note);
                $totalExpense += $amt;
            }

            // ── Utilities ──
            foreach ($this->randDays($days, random_int(1, 3)) as $d) {
                $amt = round(random_int(50, 150) * $seasonMul * $summerMul, 2);
                $note = ['Luz', 'Agua', 'Internet', 'Teléfono', 'Gas'][array_rand(['Luz', 'Agua', 'Internet', 'Teléfono', 'Gas'])];
                $batch[] = $this->tx($user->id, 'Servicios', $ym, $d, $amt, $note);
                $totalExpense += $amt;
            }

            // ── Entertainment ──
            foreach ($this->randDays($days, random_int(2, 6)) as $d) {
                $amt = round(random_int(10, 100) * $seasonMul * $decMul, 2);
                $note = ['Cine', 'Netflix', 'Spotify', 'Bar', 'Concierto'][array_rand(['Cine', 'Netflix', 'Spotify', 'Bar', 'Concierto'])];
                $batch[] = $this->tx($user->id, 'Entretenimiento', $ym, $d, $amt, $note);
                $totalExpense += $amt;
            }

            // ── Health (30%) ──
            if (random_int(1, 100) <= 30) {
                $amt = round(random_int(20, 200) * $seasonMul, 2);
                $note = ['Consulta médica', 'Farmacia', 'Dentista'][array_rand(['Consulta médica', 'Farmacia', 'Dentista'])];
                $batch[] = $this->tx($user->id, 'Salud', $ym, random_int(1, $days), $amt, $note);
                $totalExpense += $amt;
            }

            // ── Shopping ──
            foreach ($this->randDays($days, random_int(1, 4)) as $d) {
                $amt = round(random_int(20, 300) * $seasonMul * $decMul, 2);
                $note = ['Ropa', 'Hogar', 'Regalo', 'Electrónicos', 'Libros'][array_rand(['Ropa', 'Hogar', 'Regalo', 'Electrónicos', 'Libros'])];
                $batch[] = $this->tx($user->id, 'Compras', $ym, $d, $amt, $note);
                $totalExpense += $amt;
            }

            // ── Big outlier (5%) ──
            if (random_int(1, 100) <= 5) {
                $amt = random_int(500, 2000);
                $note = ['Viaje', 'Seguro anual', 'Electrodoméstico'][array_rand(['Viaje', 'Seguro anual', 'Electrodoméstico'])];
                $batch[] = $this->tx($user->id, 'Compras', $ym, random_int(1, $days), $amt, $note);
                $totalExpense += $amt;
            }

            if (count($batch) >= 50) {
                $totalTx += count($batch);
                Transaction::insert($batch);
                $batch = [];
            }

            $this->output->progressAdvance();
        }

        if ($batch) {
            $totalTx += count($batch);
            Transaction::insert($batch);
        }

        $this->output->progressFinish();
        $this->info("  ✓ {$totalTx} transacciones generadas");
        $this->newLine();
        $this->line('  Total ingresos:  $' . number_format($totalIncome, 2));
        $this->line('  Total gastos:    $' . number_format($totalExpense, 2));
        $this->line('  Balance:         $' . number_format($totalIncome - $totalExpense, 2));
        $this->newLine();
        $this->info('  ✅ Dummy data lista.');
        $this->line('     Entra al ledger con:');
        $this->line('     Usuario: DUMMY');
        $this->line('     Password: 123.Angel');

        return self::SUCCESS;
    }

    private function ensureCategories(): void
    {
        $defs = [
            'Salario'         => ['type' => 'income',  'color_hex' => '#34C759', 'icon' => 'briefcase'],
            'Freelance'       => ['type' => 'income',  'color_hex' => '#30B0C7', 'icon' => 'laptop'],
            'Renta'           => ['type' => 'expense', 'color_hex' => '#0A84FF', 'icon' => 'home'],
            'Alimentación'    => ['type' => 'expense', 'color_hex' => '#FF9F0A', 'icon' => 'utensils'],
            'Transporte'      => ['type' => 'expense', 'color_hex' => '#FF453A', 'icon' => 'car'],
            'Servicios'       => ['type' => 'expense', 'color_hex' => '#AF52DE', 'icon' => 'zap'],
            'Entretenimiento' => ['type' => 'expense', 'color_hex' => '#FF6482', 'icon' => 'film'],
            'Salud'           => ['type' => 'expense', 'color_hex' => '#30D158', 'icon' => 'heart'],
            'Compras'         => ['type' => 'expense', 'color_hex' => '#BF5AF2', 'icon' => 'shopping-bag'],
        ];

        foreach ($defs as $name => $attr) {
            $cat = Category::firstOrCreate(
                ['name' => $name, 'user_id' => null],
                $attr
            );
            $this->catIds[$name] = $cat->id;
            $this->catType[$name] = $attr['type'];
        }
    }

    private function tx(int $userId, string $catName, string $ym, int $day, float $amount, string $note): array
    {
        return [
            'user_id' => $userId,
            'category_id' => $this->catIds[$catName],
            'date' => "{$ym}-" . str_pad((string) $day, 2, '0', STR_PAD_LEFT),
            'type' => $this->catType[$catName],
            'amount' => round(abs($amount), 2),
            'note' => $note,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function randDays(int $daysInMonth, int $count): array
    {
        $days = range(1, $daysInMonth);
        shuffle($days);
        return array_slice($days, 0, $count);
    }
}
