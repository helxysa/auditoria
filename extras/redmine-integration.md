# Integração com Redmine

Esta aplicação possui integração com a API REST do Redmine para consultar projetos, issues, trackers e outros recursos.

## Configuração

### 1. Variáveis de Ambiente

Adicione as seguintes variáveis no arquivo `.env`:

```env
REDMINE_URL=https://seu-redmine.com.br
REDMINE_API_KEY=sua_api_key_aqui
```

### 2. Como obter a API Key do Redmine

1. Acesse seu Redmine
2. Vá em **Minha conta** (My account)
3. No menu lateral direito, clique em **Mostrar** ao lado de "API access key"
4. Copie a chave e cole no arquivo `.env`

## Estrutura de Arquivos

```
app/
├── Services/
│   └── RedmineService.php      # Serviço principal para chamadas à API
├── Http/
│   └── Controllers/
│       └── RedmineController.php  # Controller com endpoints de teste
config/
└── services.php                # Configurações do Redmine
```

## Uso

### RedmineService

O serviço `RedmineService` é responsável por todas as chamadas à API do Redmine.

#### Métodos disponíveis:

##### `getProjects(): array`
Lista todos os projetos disponíveis no Redmine.

```php
use App\Services\RedmineService;

$redmineService = app(RedmineService::class);
$projects = $redmineService->getProjects();
```

##### `getProject(int $projectId): ?array`
Obtém um projeto específico por ID.

```php
$project = $redmineService->getProject(1);
```

### Página de Visualização

#### Acessar lista de projetos
Acesse a URL no navegador:
```
GET /redmine
```

A página exibirá:
- Total de projetos
- Lista de todos os projetos com:
  - Nome do projeto
  - ID e identificador
  - Descrição (se disponível)
  - Status (Ativo/Inativo)

## Testes

Para testar a integração, acesse no navegador:

```
http://localhost:8000/redmine
```

## Tratamento de Erros

Todos os erros são logados automaticamente no arquivo de log do Laravel (`storage/logs/laravel.log`).

As respostas de erro seguem o formato:

```json
{
  "success": false,
  "message": "Descrição do erro"
}
```

## Próximos Passos

Esta implementação inicial lista apenas projetos. Você pode expandir o `RedmineService` para adicionar:

- Listagem de issues
- Listagem de trackers
- Listagem de status
- Criação e atualização de issues
- Consultas com filtros avançados

Consulte a [documentação oficial da API do Redmine](https://www.redmine.org/projects/redmine/wiki/Rest_api) para mais detalhes sobre os endpoints disponíveis.
