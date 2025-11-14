# Pêndulo Radar - Sistema de Gerenciamento de Passageiros

Sistema desenvolvido em Laravel para gerenciar reservas de passageiros com integração ao Google Maps.

## 🎯 Funcionalidades

### Para Passageiros
- ✅ Formulário de reserva com nome, data/horário e endereço
- 🗺️ Integração com Google Maps (autocomplete de endereços)
- 📄 Upload de comprovante de pagamento após reserva
- ✉️ Confirmação de reserva com código único

### Para Motoristas
- 🗺️ Visualização de todos os passageiros no mapa do Google Maps
- 🔍 Filtros por data e horário
- 📊 Dashboard com estatísticas
- 👁️ Visualização de comprovantes dos passageiros
- 📍 Marcadores coloridos (verde = com comprovante, azul = sem comprovante)

### Sistema
- 🔄 Reset automático diário às 00:00
- 💾 Upload e armazenamento seguro de arquivos
- 📱 Interface responsiva

## 🚀 Instalação e Configuração

### 1. Configurar Google Maps API

Edite o arquivo `.env` e adicione sua chave do Google Maps:

```bash
GOOGLE_MAPS_API_KEY=sua_chave_aqui
```

**Importante:** Você precisa ativar as seguintes APIs no Google Cloud Console:
- Maps JavaScript API
- Places API

### 2. Banco de Dados

O banco já está configurado e as migrations foram executadas. Se precisar recriar:

```bash
php artisan migrate:fresh
```

### 3. Iniciar o Servidor

```bash
php artisan serve
```

O sistema estará disponível em: http://localhost:8000

### 4. Configurar o Agendamento (Reset Diário)

Para que o sistema resete os dados automaticamente à meia-noite, adicione ao cron:

```bash
* * * * * cd /home/marco/pendulo-radar && php artisan schedule:run >> /dev/null 2>&1
```

Ou execute manualmente quando quiser limpar os dados:

```bash
php artisan app:reset-daily
```

## 📋 Estrutura do Projeto

```
app/
├── Console/Commands/
│   └── ResetDailyData.php          # Comando para reset diário
├── Http/Controllers/
│   ├── HomeController.php           # Tela inicial
│   ├── PassengerController.php      # Gestão de passageiros
│   └── DriverController.php         # Painel do motorista
└── Models/
    └── Passenger.php                # Model de passageiros

resources/views/
├── layouts/
│   └── app.blade.php                # Layout base
├── home.blade.php                   # Tela inicial (escolha)
├── passenger/
│   ├── create.blade.php             # Formulário de reserva
│   └── success.blade.php            # Confirmação + upload
└── driver/
    └── index.blade.php              # Painel com mapa

database/migrations/
└── 2024_01_01_000003_create_passengers_table.php
```

## 🎨 Identidade Visual

- **Cor Principal:** #343b71
- **Logo:** `public/logos/logo.png`
- **Gradiente:** #667eea → #343b71

## 🔧 Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rodar migrations
php artisan migrate

# Limpar dados manualmente
php artisan app:reset-daily

# Ver rotas
php artisan route:list

# Ver agendamentos
php artisan schedule:list
```

## 📝 Fluxo de Uso

### Passageiro
1. Acessa a home e clica em "Sou Passageiro"
2. Preenche o formulário (nome, horário, endereço)
3. Seleciona o local no mapa ou usa o autocomplete
4. Confirma a reserva
5. (Opcional) Anexa comprovante de pagamento

### Motorista
1. Acessa a home e clica em "Sou Motorista"
2. Visualiza todos os passageiros no mapa
3. Pode filtrar por data/horário
4. Clica nos marcadores para ver detalhes
5. Pode visualizar comprovantes dos passageiros

## 🔐 Segurança

- Upload de arquivos limitado a: JPG, PNG, PDF
- Tamanho máximo: 2MB
- Arquivos armazenados em `storage/app/public/receipts`
- CSRF protection habilitado em todos os formulários

## 🛠️ Tecnologias

- **Framework:** Laravel 11
- **Frontend:** Blade Templates + CSS3
- **Banco de Dados:** MySQL
- **APIs:** Google Maps JavaScript API, Google Places API
- **PHP:** 8.4+

## 📞 Suporte

Para problemas com Google Maps, verifique:
1. Se a chave API está configurada corretamente no `.env`
2. Se as APIs necessárias estão ativadas no Google Cloud Console
3. Se há limites de uso ou restrições na chave

---

**Desenvolvido com ❤️ usando Laravel**
