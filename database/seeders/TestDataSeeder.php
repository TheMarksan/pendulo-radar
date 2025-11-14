<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Route;
use App\Models\Stop;
use App\Models\Car;
use App\Models\Driver;
use App\Models\Passenger;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar tabelas
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Passenger::truncate();
        \DB::table('trip_progress')->truncate();
        Stop::truncate();
        Route::truncate();
        Driver::truncate();
        Car::truncate();
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Criar admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pendulo.com',
            'password' => Hash::make('admin123'),
        ]);

        // Criar rota com retorno
        $route = Route::create([
            'name' => 'Centro - Bairros',
            'description' => 'Rota principal do centro para os bairros',
            'has_return' => true,
            'is_active' => true,
        ]);

        // Criar motorista
        $driver = Driver::create([
            'name' => 'José Silva',
            'email' => 'motorista@pendulo.com',
            'password' => Hash::make('motorista123'),
            'phone' => '(11) 98765-4321',
            'route_id' => $route->id,
            'access_key' => 'MOTORISTA123',
        ]);

        // Criar carro
        $car = Car::create([
            'name' => 'Mercedes-Benz Sprinter ABC-1234',
            'driver_id' => $driver->id,
            'route_id' => $route->id,
            'departure_time' => '08:00:00',
            'return_time' => '18:00:00',
            'is_active' => true,
        ]);

        // Nomes de paradas - São Miguel dos Campos, Alagoas
        $stopNames = [
            ['name' => 'Prefeitura Municipal', 'address' => 'Praça Monsenhor Maciel, Centro', 'lat' => -9.7817, 'lng' => -36.0956],
            ['name' => 'Igreja Matriz', 'address' => 'Praça da Matriz, Centro', 'lat' => -9.7825, 'lng' => -36.0963],
            ['name' => 'Unimed São Miguel', 'address' => 'Rua Barão de Atalaia, Centro', 'lat' => -9.7830, 'lng' => -36.0970],
            ['name' => 'Praça de Eventos', 'address' => 'Av. Presidente Castelo Branco, Centro', 'lat' => -9.7840, 'lng' => -36.0985],
            ['name' => 'Caixa Econômica', 'address' => 'Rua Coronel Clodoaldo da Fonseca, Centro', 'lat' => -9.7850, 'lng' => -36.0995],
            ['name' => 'Hospital Geral', 'address' => 'Rua São Sebastião, Bairro Novo', 'lat' => -9.7865, 'lng' => -36.1010],
            ['name' => 'Supermercado Central', 'address' => 'Av. Barão de Atalaia, Centro', 'lat' => -9.7822, 'lng' => -36.0948],
            ['name' => 'Terminal Rodoviário', 'address' => 'Rua Marechal Deodoro, Centro', 'lat' => -9.7835, 'lng' => -36.0978],
            ['name' => 'Escola Estadual', 'address' => 'Rua Dom Pedro II, Bairro Novo', 'lat' => -9.7875, 'lng' => -36.1020],
            ['name' => 'Centro Comercial', 'address' => 'Av. Gov. Lamenha Filho, Centro', 'lat' => -9.7810, 'lng' => -36.0940],
        ];

        // Criar 10 paradas de ida
        $stops = [];
        foreach ($stopNames as $index => $stopData) {
            $stops[] = Stop::create([
                'name' => $stopData['name'],
                'address' => $stopData['address'],
                'route_id' => $route->id,
                'order' => $index + 1,
                'type' => 'outbound',
                'latitude' => $stopData['lat'],
                'longitude' => $stopData['lng'],
                'is_active' => true,
            ]);
        }

        // Criar 10 paradas de retorno (ordem inversa)
        foreach (array_reverse($stopNames) as $index => $stopData) {
            Stop::create([
                'name' => $stopData['name'] . ' (Retorno)',
                'address' => $stopData['address'],
                'route_id' => $route->id,
                'order' => $index + 1,
                'type' => 'return',
                'latitude' => $stopData['lat'],
                'longitude' => $stopData['lng'],
                'is_active' => true,
            ]);
        }

        // Criar 15 passageiros aleatórios
        $firstNames = ['João', 'Maria', 'Pedro', 'Ana', 'Carlos', 'Juliana', 'Lucas', 'Fernanda', 'Rafael', 'Beatriz', 'Marcos', 'Camila', 'Bruno', 'Patricia', 'Diego'];
        $lastNames = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Costa', 'Ferreira', 'Rodrigues', 'Almeida', 'Nascimento', 'Lima', 'Araújo', 'Fernandes', 'Carvalho', 'Gomes', 'Martins'];

        $today = now()->format('Y-m-d');
        $timeStarts = ['08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '14:00:00', '14:00:00', '14:00:00', '18:00:00', '18:00:00', '18:00:00', '18:00:00', '18:00:00', '18:00:00', '18:00:00'];

        foreach ($firstNames as $index => $firstName) {
            $lastName = $lastNames[$index];
            $email = strtolower($firstName . '.' . $lastName . '@email.com');

            // Alguns passageiros embarcaram (nos primeiros horários)
            $boarded = $index < 8;
            $stopId = $boarded ? $stops[rand(0, min($index, 9))]->id : $stops[rand(0, 9)]->id;

            Passenger::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => 'senha123', // 8 caracteres (limite da coluna)
                'car_id' => $car->id,
                'stop_id' => $stopId,
                'scheduled_time' => $today . ' ' . $timeStarts[$index],
                'scheduled_time_start' => $timeStarts[$index],
                'boarded_at' => $boarded ? now()->subMinutes(rand(10, 120)) : null,
                'boarded' => $boarded,
                'payment_method' => ['pix', 'dinheiro', 'vale'][rand(0, 2)],
            ]);
        }

        $this->command->info('✅ Banco limpo e populado com sucesso!');
    $this->command->info('DADOS CRIADOS:');
    $this->command->info(' - 1 Admin (admin@pendulo.com / admin123)');
    $this->command->info(' - 1 Motorista (José Silva)');
    $this->command->info(' - 1 Carro (ABC-1234)');
    $this->command->info(' - 1 Rota (Centro - Bairros) com retorno');
    $this->command->info(' - 10 Paradas de ida');
    $this->command->info(' - 10 Paradas de retorno');
    $this->command->info(' - 15 Passageiros (8 já embarcaram)');
    $this->command->info('');
    $this->command->info('Login passageiro: joao.silva@email.com / senha123');
    }
}
