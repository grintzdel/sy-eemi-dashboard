# Module Authentication

Ce module gère l'authentification des utilisateurs de l'application.

## Endpoints

### 1. Inscription (Register)

**Méthode:** `POST`
**URL:** `/api/auth/register`
**Authentification:** Aucune (endpoint public)
**Paramètres (Body JSON):**

```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "SecurePassword123!",
  "role": "ROLE_ARTIST"
}
```

**Validations:**
- `name` : Requis, nom de l'utilisateur
- `email` : Requis, doit être un email valide et unique
- `password` : Requis, doit respecter les critères de sécurité
- `role` : Optionnel, valeurs possibles: `ROLE_USER` (par défaut), `ROLE_ARTIST`

**Réponse (201 Created):**
```json
{
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI1NTBlODQwMC1lMjliLTQxZDQtYTcxNi00NDY2NTU0NDAwMDAiLCJlbWFpbCI6ImpvaG4uZG9lQGV4YW1wbGUuY29tIiwicm9sZSI6IlJPTEVfVVNFUiIsImV4cCI6MTcwNTMzNjgwMH0.abc123xyz456",
    "expiresIn": 3600
  }
}
```

**Comportement particulier:**
- L'utilisateur est créé avec le rôle `ROLE_USER` par défaut si aucun rôle n'est spécifié.
- Si le paramètre `role` est fourni avec la valeur `ROLE_ARTIST`, l'utilisateur sera créé en tant qu'artiste.
- Le mot de passe est hashé avant d'être stocké.
- Un token JWT est généré et retourné immédiatement.
- Le token a une durée de validité de 3600 secondes (1 heure).

**Erreurs possibles:**
- **400 Bad Request:** Données de validation échouées
- **422 Unprocessable Entity:** Email déjà utilisé

---

### 2. Connexion (Login)

**Méthode:** `POST`
**URL:** `/api/auth/login`
**Authentification:** Aucune (endpoint public)
**Paramètres (Body JSON):**

```json
{
  "email": "john.doe@example.com",
  "password": "SecurePassword123!"
}
```

**Validations:**
- `email` : Requis, email de l'utilisateur
- `password` : Requis, mot de passe de l'utilisateur

**Réponse (200 OK):**
```json
{
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI1NTBlODQwMC1lMjliLTQxZDQtYTcxNi00NDY2NTU0NDAwMDAiLCJlbWFpbCI6ImpvaG4uZG9lQGV4YW1wbGUuY29tIiwicm9sZSI6IlJPTEVfQVJUSVNUIiwiZXhwIjoxNzA1MzM2ODAwfQ.def789ghi012",
    "expiresIn": 3600
  }
}
```

**Comportement particulier:**
- Le mot de passe fourni est vérifié contre le hash stocké en base de données.
- Un nouveau token JWT est généré à chaque connexion.
- Le token contient les informations de l'utilisateur (id, email, role).
- Le token a une durée de validité de 3600 secondes (1 heure).

**Erreurs possibles:**
- **401 Unauthorized:** Email ou mot de passe incorrect
- **400 Bad Request:** Données de validation échouées

---

## Utilisation du token JWT

Une fois authentifié, le token doit être inclus dans l'en-tête `Authorization` de toutes les requêtes protégées:

```
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Exemple de requête avec token:**
```bash
curl -X GET https://api.example.com/api/songs \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

## Structure de données

### TokenViewModel
| Champ | Type | Description |
|-------|------|-------------|
| token | string | Token JWT pour l'authentification |
| expiresIn | integer | Durée de validité du token en secondes (3600 = 1 heure) |

## Flux d'authentification

1. **Inscription:** L'utilisateur s'inscrit via `/api/auth/register` et reçoit un token
2. **Connexion:** L'utilisateur se connecte via `/api/auth/login` et reçoit un token
3. **Utilisation:** Le token est envoyé dans l'en-tête `Authorization` pour accéder aux endpoints protégés
4. **Expiration:** Après 1 heure, le token expire et l'utilisateur doit se reconnecter

## Sécurité

- Les mots de passe sont hashés avec un algorithme sécurisé (bcrypt)
- Les tokens JWT sont signés pour empêcher la falsification
- Les tokens expirent après 1 heure pour limiter les risques en cas de vol
- L'email doit être unique dans la base de données

## Codes d'erreur

- **400 Bad Request:** Données invalides (validation échouée)
- **401 Unauthorized:** Identifiants incorrects
- **422 Unprocessable Entity:** Email déjà utilisé (lors de l'inscription)
