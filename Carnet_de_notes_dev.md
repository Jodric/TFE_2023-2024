# Carnet de notes dev

*PHP 8 · Laravel · Docker · MySQL · Tests · Git*

Mes notes perso, complétées au fil des projets et des révisions. Commandes, bouts de code testés, pièges rencontrés et méthodes qui marchent.

## Sommaire

1. Méthode : avant de coder et pour déboguer
2. Docker et Docker Compose
3. Laravel : commandes artisan
4. Laravel : routes et API REST
5. Laravel : base de données (migrations, modèles, Eloquent)
6. Laravel : le parcours d'une requête (validation, contrôleur, service, réponse)
7. Tests avec PHPUnit
8. PHP 8 : rappels
9. MySQL : requêtes et notions
10. Git : l'essentiel
11. Bonnes pratiques : manipuler de l'argent
12. Logique : fiches méthodes
13. Liens de documentation

---

## 1. Méthode : avant de coder et pour déboguer

### Face à un exercice pratique

1. **Reformuler** la demande en une ou deux phrases.
2. **Questions** : noter ce qui est flou, et l'hypothèse que je prends en attendant.
3. **Exemple concret** : une entrée, la sortie attendue, et les cas limites (vide, zéro, négatif, doublon, inexistant).
4. **Plan** : les fichiers à toucher, dans l'ordre.
5. **Coder petit, tester souvent** : une étape, un test ou une requête, puis la suivante.
6. **Bilan** : ce qui marche, ce qui manque, ce que j'améliorerais avec plus de temps.

> 💡 **Astuce :** Penser à voix haute. Une solution incomplète bien expliquée vaut mieux qu'une solution parfaite en silence. Si je bloque plus de 5 minutes : dire où, proposer une piste, demander si je peux faire une hypothèse.

#### Modèle de NOTES.md

```markdown
# Fonctionnalité : <titre>

## Reformulation
<ce que j'ai compris de la demande>

## Questions / hypothèses
- Question : ... -> Hypothèse retenue : ...

## Plan
1. routes/api.php : la route
2. Controller : la méthode
3. tests/Feature : les tests

## Difficultés
- <ce qui a bloqué> -> <comment je l'ai résolu, source consultée>

## Bilan
- OK : ...
- Manque : ...
- Avec plus de temps : ...
```

### Lire une erreur (dans cet ordre)

1. **Le type** : `ParseError` (syntaxe), `QueryException` (SQL), `ValidationException`...
2. **Le message** : la phrase clé dit souvent exactement ce qui manque.
3. **Le fichier et la ligne** : là où l'erreur a été détectée (pas toujours là où elle est née).
4. **Ce qui marche déjà** : si le message cite `Host: db, Database: ...`, la connexion fonctionne, le problème est ailleurs.

| Ce que je vois | Ce que ça veut dire | Piste |
| --- | --- | --- |
| « Site inaccessible » | Aucun serveur ne répond | Conteneurs arrêtés : `docker compose ps` |
| 404 Not Found | Route ou ressource inexistante | `route:list`, id inexistant |
| 405 Method Not Allowed | L'URL existe, pas avec ce verbe | GET / POST / DELETE ? |
| 422 Unprocessable | Validation refusée (erreur du client) | Lire `errors` dans le JSON |
| 500 Server Error | L'application plante (erreur du code) | Lire `message`, `file`, `line` |
| ParseError | Fichier PHP illisible (syntaxe) | Accolade, point-virgule, parenthèse |
| `{"data": []}` | Tout marche, la table est vide | Seeder ? Bon compte ? |

> ⚠️ **Piège :** Tout plante d'un coup alors que j'ai touché à une seule chose : erreur de syntaxe dans le dernier fichier modifié. Astuce VS Code : cliquer à côté d'une accolade pour voir celle qui lui correspond.

> ⚠️ **Piège :** Fichier non enregistré : Docker lit le disque, pas l'éditeur. Un point blanc sur l'onglet = pas sauvegardé. Activer Fichier > Enregistrement automatique.

---

## 2. Docker et Docker Compose

Docker fait tourner chaque service dans une boîte isolée (conteneur), avec les mêmes versions partout. Toute la configuration tient dans un fichier texte : un collègue obtient la même stack en une commande.

| Notion | En une phrase |
| --- | --- |
| Image | La recette figée (système + logiciels). On la reconstruit, on ne la modifie pas. |
| Dockerfile | Le fichier qui décrit comment construire une image. |
| Conteneur | Une image qui tourne. Jetable : ce qu'il écrit disparaît avec lui. |
| Volume nommé | Stockage qui survit à la suppression du conteneur (ex. données MySQL). |
| Bind mount | Dossier de mon PC partagé avec le conteneur (ex. `./src` = mon code). |
| Port `8080:80` | `hôte:conteneur`. localhost:8080 sur le PC arrive sur le port 80 du conteneur. |
| Réseau Compose | Les services se trouvent par leur NOM : `DB_HOST=db`, pas `localhost`. |

> 💡 **Astuce :** Chaque conteneur a son propre localhost. Dans le conteneur PHP, localhost = le conteneur PHP lui-même. Pour joindre MySQL : le nom du service.

### Commandes de base

| Commande | Rôle |
| --- | --- |
| `docker compose up -d` | Créer et démarrer tous les services en arrière-plan |
| `docker compose ps` | État des conteneurs |
| `docker compose logs -f app` | Logs en direct d'un service (Ctrl+C pour quitter) |
| `docker compose exec app bash` | Ouvrir un terminal DANS un conteneur qui tourne |
| `docker compose exec app php artisan ...` | Lancer une commande dans le conteneur `app` |
| `docker compose run --rm app ...` | Conteneur TEMPORAIRE pour une commande (supprimé après) |
| `docker compose run --rm --no-deps app ...` | Idem, sans démarrer les dépendances (ex. la base) |
| `docker compose build` | Reconstruire l'image après une modif du Dockerfile |
| `docker compose stop` | Arrêter, en gardant tout |
| `docker compose down` | Supprimer les conteneurs (les volumes restent) |
| `docker compose down -v` | Supprimer aussi les volumes : base de données effacée |
| `docker images` / `docker ps -a` | Lister images / tous les conteneurs |
| `docker compose exec db mysql -uapp -psecret nom_base` | Console MySQL |

> 💡 **Astuce :** `exec` = dans un conteneur déjà en marche. `run` = nouveau conteneur temporaire. Après `down -v`, la base est vide : il faut relancer `php artisan migrate --seed`.

### Template docker-compose.yml (Laravel + Nginx + MySQL + phpMyAdmin)

```yaml
services:
  app:                      # PHP-FPM + Composer
    build: ./docker/php
    command: sh -c "chmod -R 777 storage bootstrap/cache 2>/dev/null; php-fpm"
    volumes:
      - ./src:/var/www/html
    depends_on:
      - db

  web:                      # Nginx -> passe les .php a PHP-FPM
    image: nginx:1.27-alpine
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

  db:                       # MySQL
    image: mysql:8.4
    environment:
      MYSQL_DATABASE: app_db
      MYSQL_USER: app
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin:5.2
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_USER: app
      PMA_PASSWORD: secret
    depends_on:
      - db

volumes:
  dbdata:
```

> ⚠️ **Piège :** YAML : l'indentation compte, et uniquement des ESPACES (jamais de tabulation). Deux services ne peuvent pas utiliser le même port côté PC.

### Template Dockerfile PHP

```dockerfile
FROM php:8.4-fpm

RUN apt-get update && apt-get install -y git unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql zip intl bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
```

### Nginx : docker/nginx/default.conf

```nginx
server {
    listen 80;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;   # nom du service PHP + port PHP-FPM
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
}
```

### Nouveau projet Laravel sous Docker, de zéro

```bash
docker compose build
docker compose run --rm --no-deps app composer create-project laravel/laravel .
docker compose up -d
# modifier src/.env (bloc ci-dessous)
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan test
# navigateur : http://localhost:8080
```

Bloc base de données dans `src/.env` :

```ini
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=app_db
DB_USERNAME=app
DB_PASSWORD=secret
```

### Dépannage Docker

| Symptôme | Cause | Solution |
| --- | --- | --- |
| `port is already allocated` | Un autre programme (XAMPP, WAMP) prend le port | L'arrêter, ou changer `"3307:3306"` |
| `failed to copy ... EOF` au pull | Téléchargement coupé (réseau) | Relancer. Sinon Docker Engine : `"registry-mirrors": ["https://mirror.gcr.io"]` |
| `Permission denied` sur storage/logs | PHP-FPM ne peut pas écrire | `chmod -R 777 storage bootstrap/cache` dans le conteneur |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL démarre encore, ou mauvais DB_HOST | Attendre 30 s, `docker compose logs db`, vérifier `.env` |
| `Table '...' doesn't exist` | Base vide (ex. après `down -v`) | `php artisan migrate --seed` |
| `Project directory is not empty` | create-project dans un dossier non vide | Vider ou supprimer `src` |

---

## 3. Laravel : commandes artisan

Sous Docker, préfixer par `docker compose exec app`.

| Commande | Rôle |
| --- | --- |
| `php artisan make:model Account -mf` | Modèle + migration (-m) + factory (-f) |
| `php artisan make:migration create_x_table` | Nouvelle migration |
| `php artisan make:controller Api/AccountController --api` | Contrôleur API (index, store, show, update, destroy) |
| `php artisan make:request StoreTransactionRequest` | Classe de validation (FormRequest) |
| `php artisan make:resource AccountResource` | Mise en forme du JSON |
| `php artisan make:test AccountApiTest` | Test Feature (`--unit` pour un test unitaire) |
| `php artisan make:seeder` / `make:factory` | Données de démo / de test |
| `php artisan migrate` | Exécuter les migrations pas encore lancées |
| `php artisan migrate --seed` | Idem + seeders |
| `php artisan migrate:fresh --seed` | Tout supprimer, tout recréer (dev uniquement !) |
| `php artisan migrate:rollback` | Annuler le dernier lot (batch) |
| `php artisan route:list --path=api` | Lister les routes |
| `php artisan tinker` | Console PHP dans l'application |
| `php artisan test --filter=NomDuTest` | Lancer un test ou une classe de tests |
| `php artisan install:api` | Activer routes/api.php + Sanctum (Laravel 11+) |
| `php artisan config:clear` / `cache:clear` | Vider les caches si un changement n'est pas pris |

> ⚠️ **Piège :** Deux familles de commandes : `make:*` GÉNÈRE des fichiers de code et ne touche jamais à la base. `migrate` EXÉCUTE les migrations sur la base. (Le plan de la maison vs construire la maison.)

> 💡 **Astuce :** La table `migrations` est le registre des migrations déjà exécutées (nom + batch). `migrate` ne lance que les nouvelles. Les migrations = le versionnement du schéma, comme Git pour le code.

---

## 4. Laravel : routes et API REST

REST : l'URL désigne la **ressource** (un nom), le **verbe HTTP** dit l'action. Prévisible pour tout le monde.

| Verbe + URL | Action | Méthode du contrôleur | Code succès |
| --- | --- | --- | --- |
| GET /accounts | Lister | index | 200 |
| POST /accounts | Créer | store | 201 |
| GET /accounts/{id} | Lire un élément | show | 200 |
| PUT/PATCH /accounts/{id} | Modifier | update | 200 |
| DELETE /accounts/{id} | Supprimer | destroy | 204 |

```php
// routes/api.php  (tout est préfixé par /api)
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('accounts', AccountController::class)->only(['index', 'store', 'show']);
Route::get('accounts/{account}/transactions', [TransactionController::class, 'index']);
Route::post('accounts/{account}/transactions', [TransactionController::class, 'store']);
Route::get('accounts/{account}/summary', [AccountController::class, 'summary']);
Route::post('transfers', [TransactionController::class, 'transfer']);
```

Si `routes/api.php` n'est pas chargé (Laravel 11+), soit `php artisan install:api`, soit dans `bootstrap/app.php` :

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',   // ajoute le préfixe /api
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

> 💡 **Astuce :** `->only([...])` limite apiResource. Une route absente avec ce verbe donne 405. Le nom `{account}` dans l'URL doit correspondre au paramètre `Account $account` du contrôleur (route model binding).

#### Codes HTTP à connaître

| Code | Sens |
| --- | --- |
| 200 OK / 201 Created / 204 No Content | Succès / ressource créée / succès sans contenu |
| 401 Unauthorized / 403 Forbidden | Pas connecté / connecté mais pas le droit |
| 404 Not Found / 405 Method Not Allowed | Ressource inexistante / mauvais verbe |
| 422 Unprocessable Content | Validation refusée : erreur du CLIENT |
| 500 Internal Server Error | Le code a planté : erreur du DÉVELOPPEUR |

---

## 5. Laravel : base de données

### Migration

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit']);
            $table->bigInteger('amount');              // centimes, jamais de float
            $table->bigInteger('balance_after');
            $table->string('reference', 64)->unique();  // clé d'idempotence
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['account_id', 'created_at']); // index composé
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
```

### Modèle Eloquent

```php
class Account extends Model
{
    use HasFactory;

    // Champs remplissables via create()/fill() : protection "mass assignment"
    protected $fillable = ['holder_name', 'iban', 'currency', 'balance'];

    // Valeurs par défaut côté PHP (sinon null juste après create())
    protected $attributes = ['balance' => 0, 'currency' => 'EUR'];

    protected function casts(): array
    {
        return ['balance' => 'integer'];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}

// Côté Transaction :
public function account(): BelongsTo
{
    return $this->belongsTo(Account::class);
}
```

> 💡 **Astuce :** Un modèle = une TABLE. Pas de table, pas de modèle. Pour mettre en forme une réponse : un tableau ou une Resource. Nom de classe en majuscule (`Account`), fichier du même nom.

### Factory et seeder

```php
// database/factories/AccountFactory.php
public function definition(): array
{
    return [
        'holder_name' => fake()->name(),
        'iban' => 'BE'.fake()->unique()->numerify('##############'),
        'currency' => 'EUR',
        'balance' => 10000,   // 100,00 EUR
    ];
}

// database/seeders/DatabaseSeeder.php
public function run(): void
{
    Account::factory()->create(['holder_name' => 'Alice', 'balance' => 50000]);
    Account::factory()->count(5)->create();
}
```

### Eloquent : aide-mémoire

```php
Account::all();
Account::find($id);                    // null si absent
Account::findOrFail($id);              // 404 si absent
Account::where('balance', '>', 0)->orderBy('id')->get();
Account::create($data);                // respecte $fillable
$account->update(['holder_name' => 'Bob']);
$account->transactions()->create([...]);       // FK remplie automatiquement
$account->transactions()->latest('id')->paginate(20);

// Agrégats
$account->transactions()->count();
$account->transactions()->where('type', 'credit')->sum('amount');
$account->transactions()->avg('amount');

// Plusieurs totaux en UNE requête
$totals = $account->transactions()
    ->selectRaw('type, SUM(amount) as total')
    ->groupBy('type')
    ->pluck('total', 'type');          // ['credit' => '4250', 'debit' => '300']
$credit = (int) $totals->get('credit', 0);     // cast + valeur par défaut

// Problème N+1 : une requête par ligne dans une boucle
$accounts = Account::with('transactions')->get();   // eager loading : 2 requêtes au total

// Verrou (dans une transaction uniquement)
Account::whereKey($id)->lockForUpdate()->firstOrFail();
```

> ⚠️ **Piège :** `SUM()` renvoie une CHAÎNE depuis MySQL ("4250"). Toujours caster `(int)` avant de renvoyer du JSON. En JavaScript, "4250" + 100 = "4250100".

---

## 6. Laravel : le parcours d'une requête

Requête HTTP → middleware → route → **route model binding** (404) → **FormRequest** (422) → **contrôleur** → **service** → modèle / base → **Resource** → réponse JSON.

| Couche | Rôle | Règle |
| --- | --- | --- |
| FormRequest | Valider les entrées | Ne jamais faire confiance au client |
| Contrôleur | Chef d'orchestre HTTP | Fin : pas de calcul, pas de SQL |
| Service | Logique métier | Réutilisable (API, webhook, commande) et testable sans HTTP |
| Resource | Format du JSON | On choisit les champs exposés |

### Validation : FormRequest

```php
class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'integer', 'min:1'],   // centimes, > 0
            'reference' => ['required', 'string', 'max:64'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
```

Validation directe dans un contrôleur :

```php
$data = $request->validate([
    'holder_name' => ['required', 'string', 'max:255'],
    'iban' => ['required', 'string', 'max:34', 'unique:accounts,iban'],
    'from_account_id' => ['required', 'integer', 'exists:accounts,id'],
    'to_account_id' => ['required', 'integer', 'exists:accounts,id', 'different:from_account_id'],
]);
```

> ⚠️ **Piège :** `$request->validated()` ne renvoie QUE les champs qui ont une règle (un champ piégé est ignoré). `$request->all()` renvoie tout : ne jamais l'utiliser pour écrire en base.

### Contrôleur fin + injection de dépendance

```php
class TransactionController extends Controller
{
    // Laravel crée le service et l'injecte
    public function __construct(private PaymentService $payments) {}

    public function store(StoreTransactionRequest $request, Account $account)
    {
        $data = $request->validated();

        $tx = $this->payments->apply(
            $account, $data['type'], $data['amount'], $data['reference'], $data['description'] ?? null
        );

        // 201 si créée maintenant, 200 si la référence existait déjà
        return (new TransactionResource($tx))
            ->response()
            ->setStatusCode($tx->wasRecentlyCreated ? 201 : 200);
    }
}
```

### Resource (format du JSON)

```php
class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

// Utilisation
return new TransactionResource($tx);                          // { "data": {...} }
return TransactionResource::collection($query->paginate(20)); // liste + pagination
```

### Réponse JSON sans Resource (format { data })

```php
public function summary(Account $account)
{
    $totals = $account->transactions()
        ->selectRaw('type, SUM(amount) as total')
        ->groupBy('type')
        ->pluck('total', 'type');

    return response()->json([
        'data' => [
            'total_credit' => (int) $totals->get('credit', 0),
            'total_debit' => (int) $totals->get('debit', 0),
            'balance' => $account->balance,               // solde STOCKÉ
            'transactions_count' => $account->transactions()->count(),
        ],
    ]);
}
```

### Service : transaction SQL, verrou, idempotence

```php
class PaymentService
{
    public function apply(Account $account, string $type, int $amount, string $reference, ?string $description = null): Transaction
    {
        // Défense en profondeur : le service protège ses propres règles
        if (!in_array($type, ['credit', 'debit'], true)) {
            throw new \InvalidArgumentException("Type invalide : $type");
        }
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Montant invalide : $amount");
        }

        // Idempotence : même référence = même opération, pas de doublon
        $existing = Transaction::where('reference', $reference)->first();
        if ($existing) {
            return $existing;
        }

        // Tout ou rien (rollback si exception)
        return DB::transaction(function () use ($account, $type, $amount, $reference, $description) {
            // SELECT ... FOR UPDATE : bloque la ligne jusqu'au COMMIT
            $locked = Account::whereKey($account->id)->lockForUpdate()->firstOrFail();

            if ($type === 'debit' && $locked->balance < $amount) {
                throw new InsufficientFundsException();
            }

            $locked->balance += $type === 'credit' ? $amount : -$amount;
            $locked->save();

            return $locked->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $locked->balance,
                'reference' => $reference,
                'description' => $description,
            ]);
        });
    }

    public function transfer(Account $from, Account $to, int $amount, string $reference): array
    {
        return DB::transaction(fn () => [
            $this->apply($from, 'debit', $amount, $reference.'-out'),
            $this->apply($to, 'credit', $amount, $reference.'-in'),
        ]);
    }
}
```

### Exception métier qui répond en JSON

```php
class InsufficientFundsException extends Exception
{
    // Cas métier normal : pas besoin de l'écrire dans les logs d'erreur
    public function report(): void {}

    // Laravel appelle render() automatiquement
    public function render(): JsonResponse
    {
        return response()->json(['message' => 'Solde insuffisant.'], 422);
    }
}
```

---

## 7. Tests avec PHPUnit

| Type | Ce qu'il teste | Dossier |
| --- | --- | --- |
| Unitaire | Une petite fonction isolée, sans base ni HTTP | tests/Unit (étend PHPUnit\Framework\TestCase) |
| Feature | Une route complète (HTTP + base), ou un service avec la base | tests/Feature (étend Tests\TestCase) |

```bash
php artisan test                         # tous les tests
php artisan test --filter=AccountApiTest  # une classe
php artisan test --filter=test_summary    # un test
```

En test, Laravel utilise SQLite en mémoire (voir `phpunit.xml`) : rapide et isolé.

### Modèle de test Feature

```php
namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountApiTest extends TestCase
{
    use RefreshDatabase;   // base vide et propre avant CHAQUE test

    public function test_credit_increases_balance(): void
    {
        // 1. Préparer
        $account = Account::factory()->create(['balance' => 1000]);

        // 2. Agir
        $response = $this->postJson("/api/accounts/{$account->id}/transactions", [
            'type' => 'credit', 'amount' => 250, 'reference' => 'REF-1',
        ]);

        // 3. Vérifier
        $response->assertCreated()
            ->assertJsonPath('data.balance_after', 1250);
        $this->assertSame(1250, $account->fresh()->balance);
    }

    public function test_summary_of_empty_account_returns_zeros(): void
    {
        $account = Account::factory()->create(['balance' => 0]);

        $this->getJson("/api/accounts/{$account->id}/summary")
            ->assertOk()
            ->assertJsonPath('data.total_credit', 0)
            ->assertJsonPath('data.transactions_count', 0);
    }
}
```

### Tester une exception (appel direct du service)

```php
public function test_service_refuses_negative_amount(): void
{
    $account = Account::factory()->create(['balance' => 1000]);
    $service = app(PaymentService::class);   // Laravel construit le service

    $this->expectException(\InvalidArgumentException::class);   // AVANT l'appel

    $service->apply($account, 'credit', -500, 'REF-NEG');
}
```

### Assertions utiles

| Assertion | Vérifie |
| --- | --- |
| `assertOk()` / `assertCreated()` / `assertNotFound()` | Code 200 / 201 / 404 |
| `assertStatus(422)` | Un code précis |
| `assertJsonPath('data.total', 500)` | Une valeur ET son type ("500" ≠ 500) |
| `assertJson([...])` | Un sous-ensemble du JSON (comparaison souple) |
| `assertJsonCount(3, 'data')` | Nombre d'éléments |
| `assertJsonValidationErrors(['type', 'amount'])` | Champs refusés par la validation |
| `assertDatabaseHas('accounts', ['iban' => '...'])` | Une ligne existe en base |
| `assertDatabaseCount('transactions', 0)` | Nombre de lignes |
| `assertSame(1250, $account->fresh()->balance)` | Valeur stricte (`fresh()` relit la base) |
| `expectException(X::class)` | Une exception est lancée (à placer avant l'appel) |

### Règles que je retiens

- **Un test = un scénario.** Le nom dit le cas : `test_debit_with_insufficient_funds_is_refused`. Un échec se comprend sans ouvrir le code.
- **Cas limites** : zéro, vide, négatif, doublon, inexistant. Les bugs se cachent aux frontières (`<` vs `<=`).
- **Rouge d'abord** : le test doit échouer AVANT le correctif, avec un message `FAILED`. Un test qui ne peut pas échouer ne protège rien.
- **Compter les tests** : j'en ajoute un, le total doit augmenter, et son nom doit apparaître.
- `risky (0 assertions)` = le test n'a rien vérifié.
- La stack trace indique la ligne exécutée : elle dit où chercher.
- Préférer `assertJsonPath` (strict) à `assertJson` (souple) pour les montants.

---

## 8. PHP 8 : rappels

```php
declare(strict_types=1);

// Propriétés promues + readonly
final class Payment
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency = 'EUR',
    ) {}
}

// Enum (8.1)
enum Status: string { case Pending = 'pending'; case Paid = 'paid'; }

// match : strict (===), renvoie une valeur
$label = match ($status) {
    Status::Paid => 'Payé',
    default => 'En attente',
};

$city = $user?->address?->city;           // nullsafe
$desc = $data['description'] ?? null;     // valeur par défaut si absent
$p = new Payment(amount: 1250);           // arguments nommés

// Tableaux
$total  = array_sum(array_map(fn ($t) => $t['amount'], $rows));
$debits = array_filter($rows, fn ($t) => $t['type'] === 'debit');
usort($rows, fn ($a, $b) => $b['created_at'] <=> $a['created_at']);  // tri décroissant
in_array($type, ['credit', 'debit'], true);   // true = comparaison stricte
array_key_exists('ref', $row);
count($rows);

// Compter les doublons en O(n)
$seen = []; $dupes = [];
foreach ($rows as $r) {
    if (isset($seen[$r['ref']])) { $dupes[] = $r['ref']; }
    $seen[$r['ref']] = true;
}

// Exceptions
try {
    $service->apply(...);
} catch (InsufficientFundsException $e) {
    // ...
} finally {
    // toujours exécuté
}
```

| Élément | À retenir |
| --- | --- |
| `%` (modulo) | Reste de la division. `$i % 2 === 0` : pair. `$i % 2 === 1` : impair. |
| `===` vs `==` | `===` compare valeur ET type. Toujours le préférer. |
| `<=>` | Opérateur « vaisseau » : -1, 0 ou 1. Pratique dans `usort`. |
| `\InvalidArgumentException` | Le `\` = classe native de PHP (namespace racine). |
| `"Texte $var"` | Guillemets doubles : variables interprétées. Simples : texte brut. |
| Interface / abstract / trait | Contrat / classe à compléter / code partagé entre classes. |
| PSR-4 / PSR-12 | Namespace = dossier (autoload Composer) / style de code. |

> 💡 **Astuce :** Lire du code : faire un tableau (variable, condition, calcul, valeur) avec une ligne par tour de boucle. Jamais de tête.

---

## 9. MySQL : requêtes et notions

```sql
-- Nombre de transactions par compte (y compris les comptes sans transaction)
SELECT a.id, a.holder_name, a.balance, COUNT(t.id) AS nb_tx
FROM accounts a
LEFT JOIN transactions t ON t.account_id = a.id
GROUP BY a.id, a.holder_name, a.balance
ORDER BY nb_tx DESC;

-- Total débité par jour sur 30 jours
SELECT DATE(created_at) AS jour, SUM(amount) / 100 AS total_eur
FROM transactions
WHERE type = 'debit' AND created_at >= NOW() - INTERVAL 30 DAY
GROUP BY DATE(created_at)
HAVING total_eur > 0;

-- Comptes jamais débités
SELECT * FROM accounts a
WHERE NOT EXISTS (
    SELECT 1 FROM transactions t WHERE t.account_id = a.id AND t.type = 'debit'
);

-- Les 5 comptes qui ont le plus dépensé ce mois-ci
SELECT account_id, SUM(amount) AS depense
FROM transactions
WHERE type = 'debit' AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
GROUP BY account_id
ORDER BY depense DESC
LIMIT 5;

-- Transaction SQL avec verrou
START TRANSACTION;
SELECT balance FROM accounts WHERE id = 1 FOR UPDATE;
UPDATE accounts SET balance = balance - 500 WHERE id = 1;
UPDATE accounts SET balance = balance + 500 WHERE id = 2;
COMMIT;   -- ou ROLLBACK;

-- L'index est-il utilisé ?
EXPLAIN SELECT * FROM transactions WHERE account_id = 1 ORDER BY created_at DESC;
```

| Notion | À retenir |
| --- | --- |
| INNER JOIN / LEFT JOIN | Seulement les correspondances / toutes les lignes de gauche (NULL sinon). |
| WHERE / HAVING | Filtre AVANT le regroupement / APRÈS le GROUP BY. |
| Index | Accélère WHERE, JOIN, ORDER BY. Ralentit un peu les écritures. Vérifier avec EXPLAIN. |
| ACID | Atomicité (tout ou rien), Cohérence, Isolation, Durabilité. |
| Verrou FOR UPDATE | Ne vit que le temps de sa transaction. Hors transaction (autocommit), il est libéré tout de suite et ne protège rien. |
| Injection SQL | Jamais de variable concaténée dans une requête. Eloquent/PDO utilisent des requêtes préparées. |
| Type pour l'argent | BIGINT en centimes, ou DECIMAL(10,2). Jamais FLOAT. |

---

## 10. Git : l'essentiel

```bash
git clone <url>
git status                      # ce qui a changé
git diff                        # le détail des changements
git checkout -b feature/summary # nouvelle branche
git add .
git commit -m "Ajoute la route summary avec ses tests"
git push -u origin feature/summary
git pull                        # récupérer les changements
git log --oneline --graph       # historique compact
git stash / git stash pop       # mettre de côté / reprendre
git restore fichier.php         # annuler les modifs non commitées d'un fichier
```

> 💡 **Astuce :** Un commit = un changement cohérent, avec un message qui dit ce qu'il fait. Ne jamais commiter `.env` (secrets) ni `vendor/`.

---

## 11. Bonnes pratiques : manipuler de l'argent

- **Centimes, en entier.** En float, `0.1 + 0.2` ne vaut pas `0.3`. 12,50 EUR = 1250.
- **Transaction SQL** : plusieurs écritures (solde + historique, débit + crédit) réussissent ou échouent ensemble.
- **Verrou** `lockForUpdate` : deux paiements simultanés ne peuvent pas lire le même solde (race condition).
- **Idempotence** : une référence unique par opération. Si le client renvoie la requête (coupure réseau), pas de double débit : 201 la première fois, 200 ensuite.
- **Défense en profondeur** : la validation protège l'API, le service protège ses propres règles (montant > 0, type connu). Chaque couche se protège.
- **Registre (ledger)** : chaque mouvement d'argent est une transaction, y compris le solde d'ouverture. Ainsi, crédits - débits = solde, toujours vérifiable.
- **On ne supprime jamais** un compte ou une transaction : on désactive, ou on annule par une opération inverse (traçabilité, audit).
- **422 vs 500** : 422 = l'utilisateur s'est trompé ; 500 = le code s'est trompé (à loguer et corriger).
- **Deadlock** : deux virements croisés (A vers B et B vers A) peuvent se bloquer. Solution : verrouiller toujours dans le même ordre (id croissant).
- **Ce qui manque souvent en production** : authentification (Sanctum), rate limiting, logs d'audit, CI qui lance les tests à chaque push.

---

## 12. Logique : fiches méthodes

| Type | Méthode |
| --- | --- |
| Planning | Grille (jours × personnes). 1) Placer le certain. 2) Prendre la personne qui a le moins de choix. 3) Tester chaque cas jusqu'au bout, éliminer ceux qui cassent une règle. |
| Vitesses de travail | Tout ramener à 1 unité de temps (par heure, par minute). Additionner les vitesses, jamais les durées. Temps = quantité ÷ vitesse totale. |
| Lire du code | Tableau : une ligne par tour de boucle (i, condition, calcul, valeur). |
| Mot-piège | Souligner avant de calculer : un seul, au moins, sauf, avant, en même temps, par rapport à. Un seul travailleur = on additionne tout. |
| Pourcentages | % = partie ÷ base × 100. Se demander « par rapport à quoi ? ». Deux % successifs ne s'additionnent pas. |
| Déduction | « Tous les A sont B » : un non-B n'est pas A. Mais un B n'est pas forcément A. |
| Règles à appliquer | Tester chaque option contre chaque règle, dans l'ordre. Dès qu'une règle casse : éliminer. |

> 💡 **Astuce :** QCM chronométré : lire la question avant les données, partir des réponses et éliminer, ne jamais rester plus de 60 secondes sur une question.

---

## 13. Liens de documentation

| Sujet | Lien |
| --- | --- |
| Laravel (doc officielle) | https://laravel.com/docs |
| Routing | https://laravel.com/docs/routing |
| Validation (liste des règles) | https://laravel.com/docs/validation#available-validation-rules |
| Eloquent / relations | https://laravel.com/docs/eloquent |
| Query builder (agrégats, groupBy) | https://laravel.com/docs/queries |
| Migrations | https://laravel.com/docs/migrations |
| API Resources | https://laravel.com/docs/eloquent-resources |
| Tests HTTP (assertions) | https://laravel.com/docs/http-tests |
| Transactions et verrous | https://laravel.com/docs/database#database-transactions |
| PHP : manuel en français | https://www.php.net/manual/fr/ |
| PHP : fonctions de tableaux | https://www.php.net/manual/fr/ref.array.php |
| PHPUnit | https://docs.phpunit.de |
| MySQL 8.4 | https://dev.mysql.com/doc/refman/8.4/en/ |
| Docker Compose | https://docs.docker.com/compose/ |
| Git | https://git-scm.com/docs |
