# Guia de Formulários

## Como criar um formulário

### 1. Backend (Controller)

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeuModel;

class SeuController extends Controller
{
    public function index()
    {
        $items = SeuModel::orderBy('id', 'asc')->paginate(10);
        return \Inertia\Inertia::render('sua-pasta/list', [
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'idade' => ['required', 'numeric', 'min:18'],
        ]);

        SeuModel::create($validated);
        return redirect()->route('sua-rota-index');
    }
}
```

### 2. Frontend (React)

Arquivo: `resources/js/pages/sua-pasta/create-seu-form.tsx`

```tsx
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { AutoForm, AutoFormFieldConfig } from '@/components/ui/auto-form'

export default function FormSeuModel() {
    const fields: AutoFormFieldConfig[] = [
        {
            name: 'nome',
            label: 'Nome',
            type: 'text',
            required: true,
        },
        {
            name: 'email',
            label: 'E-mail',
            type: 'email',
            required: true,
        },
        {
            name: 'idade',
            label: 'Idade',
            type: 'number',
            required: true,
        },
    ]

    const initialData = {
        nome: '',
        email: '',
        idade: ''
    }

    return (
        <Card className="w-full max-w-2xl mx-auto">
            <CardHeader>
                <CardTitle>Criar Item</CardTitle>
            </CardHeader>
            <CardContent>
                <AutoForm
                    fields={fields}
                    initialData={initialData}
                    onSubmit="/sua-rota"
                    submitText="Criar"
                />
            </CardContent>
        </Card>
    )
}
```

### 3. Rotas

Arquivo: `routes/web.php`

```php
use App\Http\Controllers\SeuController;

Route::get('/sua-rota', [SeuController::class, 'index'])->name('sua-rota-index');
Route::post('/sua-rota', [SeuController::class, 'store'])->name('sua-rota-create');
```

## Regras de validação

### Básicas
- `required` - Obrigatório
- `string` - Texto
- `numeric` - Número
- `integer` - Inteiro
- `email` - Email válido
- `date` - Data válida
- `boolean` - Verdadeiro ou falso
- `url` - URL válida

### Tamanho
- `min:3` - Mínimo 3
- `max:255` - Máximo 255
- `between:1,100` - Entre 1 e 100
- `size:10` - Tamanho exato 10

### Banco de dados
- `unique:users,email` - Único na tabela users, coluna email
- `exists:categories,id` - Deve existir na tabela categories, coluna id

### Formato
- `alpha` - Apenas letras
- `alpha_num` - Letras e números
- `alpha_dash` - Letras, números e traços
- `regex:/^[0-9]+$/` - Expressão regular customizada

### Condicionais
- `required_if:tipo,pf` - Obrigatório se campo tipo for "pf"
- `required_with:endereco` - Obrigatório se campo endereco existir
- `nullable` - Pode ser vazio (use com outras regras)

## Exemplos práticos

### Formulário com select

**Controller:**
```php
public function index()
{
    $items = Item::paginate(10);
    $categorias = Categoria::all();
    
    return Inertia::render('items/list', [
        'items' => $items,
        'categorias' => $categorias
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'categoria_id' => ['required', 'exists:categorias,id'],
        'nome' => ['required', 'string', 'max:255'],
    ]);

    Item::create($validated);
    return redirect()->route('items.index');
}
```

**Frontend:**
```tsx
export default function FormItem() {
    const { categorias = [] } = usePage().props as { categorias?: any[] };

    const fields = [
        {
            name: 'categoria_id',
            label: 'Categoria',
            type: 'select',
            required: true,
            options: categorias.map(cat => ({
                value: cat.id,
                label: cat.nome
            }))
        },
        {
            name: 'nome',
            label: 'Nome',
            type: 'text',
            required: true,
        },
    ]

    const initialData = {
        categoria_id: '',
        nome: ''
    }

    return (
        <Card className="w-full max-w-2xl mx-auto">
            <CardHeader>
                <CardTitle>Criar Item</CardTitle>
            </CardHeader>
            <CardContent>
                <AutoForm
                    fields={fields}
                    initialData={initialData}
                    onSubmit="/items"
                    submitText="Criar"
                />
            </CardContent>
        </Card>
    )
}
```

### Validação avançada

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'email' => ['required', 'email', 'unique:users,email'],
        'cpf' => ['required', 'string', 'size:11', 'regex:/^[0-9]{11}$/'],
        'telefone' => ['nullable', 'string', 'min:10', 'max:11'],
        'idade' => ['required', 'integer', 'min:18', 'max:120'],
        'aceita_termos' => ['required', 'boolean', 'accepted'],
    ]);

    User::create($validated);
    return redirect()->route('users.index');
}
```

## Tipos de input disponíveis

- `text` - Texto simples
- `email` - Email
- `password` - Senha
- `number` - Número
- `select` - Lista de opções
- `textarea` - Texto longo

## Como funcionam as mensagens de erro

### Arquivo de mensagens

Arquivo: `lang/pt_BR/validation.php`

```php
return [
    // Mensagens das regras
    'required' => 'O campo :attribute é obrigatório.',
    'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'unique' => 'O :attribute já está sendo utilizado.',
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    
    // Nomes amigáveis dos campos
    'attributes' => [
        'nome' => 'nome',
        'email' => 'e-mail',
        'categoria_id' => 'categoria',
        'tipo_auditorias_id' => 'tipo de auditoria',
    ],
];
```

### Como funciona

1. Você define a regra no controller:
```php
$request->validate([
    'email' => ['required', 'email']
]);
```

2. Laravel busca a mensagem em `validation.php`:
```php
'required' => 'O campo :attribute é obrigatório.'
```

3. Laravel substitui `:attribute` pelo nome do campo definido em `attributes`:
```php
'attributes' => [
    'email' => 'e-mail',
]
```

4. Mensagem final:
```
"O campo e-mail é obrigatório."
```

5. AutoForm exibe automaticamente no frontend abaixo do campo

### Configuração necessária

**Arquivo `.env`:**
```env
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
```

**Arquivo `config/app.php`:**
```php
'locale' => env('APP_LOCALE', 'pt_BR'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'pt_BR'),
```

### Personalizando mensagens

Para mudar o nome de um campo que aparece nas mensagens:

```php
// No arquivo lang/pt_BR/validation.php
'attributes' => [
    'tipo_auditorias_id' => 'tipo de auditoria',
],
```

Resultado:
- Antes: "O campo tipo_auditorias_id é obrigatório."
- Depois: "O campo tipo de auditoria é obrigatório."

## Dicas importantes

1. Sempre use array de regras:
```php
// Correto
'campo' => ['required', 'string']

// Errado
'campo' => 'required|string'
```

2. Use `exists` para chaves estrangeiras:
```php
'categoria_id' => ['required', 'exists:categorias,id']
```

3. Use `nullable` para campos opcionais:
```php
'telefone' => ['nullable', 'string', 'max:20']
```

4. Validação acontece no backend, erros aparecem automaticamente no frontend

5. Não precisa adicionar código de erro no frontend, o AutoForm já faz isso

## Documentação completa

https://laravel.com/docs/validation
