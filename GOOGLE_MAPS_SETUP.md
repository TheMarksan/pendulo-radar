# Google Maps API Configuration

Para usar o sistema completo, você precisa:

## 1. **Obter uma chave do Google Maps API:**
   
### Passo a passo:
   
- **Acesse:** https://console.cloud.google.com/
- **Crie um projeto** ou selecione um existente
- **Ative as seguintes APIs:**
  - ✅ Maps JavaScript API
  - ✅ Places API (New)
  - ✅ Geocoding API
- **Gere uma chave de API:**
  - Vá em "Credenciais"
  - Clique em "Criar credenciais" → "Chave de API"
  - Copie a chave gerada

## 2. **Configurar a chave no sistema:**
   
Abra o arquivo `.env` na raiz do projeto e modifique:

```env
GOOGLE_MAPS_API_KEY=sua_chave_aqui
```

**Substitua `sua_chave_aqui` pela chave que você copiou.**

## 3. **Limpar cache do Laravel:**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 4. **Recarregar a página no navegador**

O sistema agora usará a nova API do Google Maps (PlaceAutocompleteElement) que é a versão recomendada e mais moderna.

---

## 🔒 Segurança (Importante para Produção)

### Restringir a chave de API:

1. No Google Cloud Console, vá em "Credenciais"
2. Clique na sua chave de API
3. Em "Restrições de aplicativo", escolha:
   - **Referenciadores HTTP (sites)** para aplicações web
   - Adicione seus domínios: `localhost:8000`, `seudominio.com`
4. Em "Restrições de API", selecione:
   - Maps JavaScript API
   - Places API (New)
   - Geocoding API

---

## ⚠️ Notas Importantes:

- **Para desenvolvimento local:** Você pode usar uma chave sem restrições temporariamente
- **Para produção:** SEMPRE adicione restrições de domínio e API
- **Custos:** O Google Maps oferece $200 de crédito gratuito por mês
- **Monitoramento:** Ative alertas de cobrança no Google Cloud Console

---

## 🆕 Nova API

O sistema foi atualizado para usar:
- ✅ **PlaceAutocompleteElement** (nova API recomendada)
- ✅ **AdvancedMarkerElement** (marcadores modernos)
- ✅ **Async/Await** (carregamento otimizado)

A API antiga (`Autocomplete`) ainda funciona mas está deprecated e será descontinuada no futuro.
