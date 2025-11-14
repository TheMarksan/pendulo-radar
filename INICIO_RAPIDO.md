# 🚀 GUIA RÁPIDO DE USO

## ✅ Sistema Instalado e Funcionando!

**Servidor rodando em:** http://127.0.0.1:8000

---

## 📝 PRÓXIMOS PASSOS

### 1. Configurar Google Maps API (IMPORTANTE)

Edite o arquivo `.env` na raiz do projeto e substitua:

```bash
GOOGLE_MAPS_API_KEY=YOUR_GOOGLE_MAPS_API_KEY
```

Por sua chave real do Google Maps. Para obter:

1. Acesse: https://console.cloud.google.com/
2. Crie/selecione um projeto
3. Ative as APIs:
   - **Maps JavaScript API**
   - **Places API (New)**
   - **Geocoding API**
4. Gere uma chave de API (Credenciais → Criar credenciais → Chave de API)
5. Cole no `.env`
6. Rode: `php artisan config:clear`

**Sem a chave do Google Maps, o mapa não funcionará!**

> 🆕 **Sistema atualizado** para usar a nova API do Google Maps (PlaceAutocompleteElement)

---

## 🎯 COMO USAR O SISTEMA

### Como Passageiro:

1. Abra http://127.0.0.1:8000
2. Clique em **"Sou Passageiro"** 🧳
3. Preencha:
   - Nome completo
   - Data e horário da viagem
   - Endereço (use o autocomplete do Google Maps)
4. Confirme a reserva
5. **Opcional:** Anexe um comprovante de pagamento (JPG, PNG ou PDF)

### Como Motorista:

1. Abra http://127.0.0.1:8000
2. Clique em **"Sou Motorista"** 🚗
3. Visualize todos os passageiros no mapa:
   - **Marcadores Azuis:** Passageiros sem comprovante
   - **Marcadores Verdes:** Passageiros com comprovante anexado
4. Use os filtros por data/horário
5. Clique nos marcadores para ver detalhes
6. Clique em "Ver Comprovante" para visualizar documentos anexados

---

## 🔄 RESET AUTOMÁTICO

O sistema limpa TODOS os dados automaticamente à meia-noite (00:00).

Para configurar o reset automático, adicione ao cron:

```bash
crontab -e
```

Adicione a linha:

```bash
* * * * * cd /home/marco/pendulo-radar && php artisan schedule:run >> /dev/null 2>&1
```

Ou limpe manualmente quando quiser:

```bash
php artisan app:reset-daily
```

---

## 🎨 IDENTIDADE VISUAL

- **Cor principal:** #343b71 (azul escuro)
- **Logo:** Coloque seu logo em `public/logos/logo.png`
- O sistema está pronto para usar sua identidade visual!

---

## 🛠️ COMANDOS ÚTEIS

```bash
# Iniciar servidor (se não estiver rodando)
php artisan serve

# Limpar todos os dados
php artisan app:reset-daily

# Ver todas as rotas
php artisan route:list

# Limpar cache
php artisan cache:clear
php artisan config:clear

# Recriar banco de dados
php artisan migrate:fresh
```

---

## 📱 TESTANDO O SISTEMA

1. **Teste como passageiro:**
   - Faça 2-3 reservas com diferentes horários
   - Anexe comprovante em algumas

2. **Teste como motorista:**
   - Veja os passageiros no mapa
   - Teste os filtros por data/hora
   - Clique nos marcadores
   - Visualize os comprovantes

---

## ⚠️ IMPORTANTE

- O mapa **só funcionará** com a chave do Google Maps configurada
- Comprovantes são salvos em `storage/app/public/receipts`
- Tamanho máximo de arquivo: 2MB
- Formatos aceitos: JPG, PNG, PDF

---

## 🎉 PRONTO!

Seu sistema está funcionando em http://127.0.0.1:8000

**Dúvidas?** Consulte o arquivo `README_SISTEMA.md` para mais detalhes técnicos.

