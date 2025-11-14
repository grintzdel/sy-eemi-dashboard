# Module User

Ce module gère les utilisateurs de l'application.

## Endpoints

### 1. Liste tous les utilisateurs

**Méthode:** `GET`
**URL:** `/api/users`
**Authentification:** ROLE_ADMIN ou ROLE_MODERATOR
**Paramètres:** Aucun

**Réponse (200 OK):**
```json
[
  {
    "data": {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "ROLE_ARTIST",
      "createdAt": "2024-01-15 10:30:00",
      "updatedAt": "2024-01-15 10:30:00"
    }
  },
  {
    "data": {
      "id": "550e8400-e29b-41d4-a716-446655440001",
      "name": "Jane Smith",
      "email": "jane.smith@example.com",
      "role": "ROLE_USER",
      "createdAt": "2024-01-16 10:30:00",
      "updatedAt": "2024-01-16 10:30:00"
    }
  }
]
```

**Comportement particulier:**
- Seuls les administrateurs et modérateurs peuvent lister tous les utilisateurs.
- Pas de pagination (paginationEnabled: false).

---

### 2. Récupère un utilisateur par ID

**Méthode:** `GET`
**URL:** `/api/users/{id}`
**Authentification:** ROLE_USER (utilisateur authentifié)
**Paramètres:**
- `id` (string, UUID) - Identifiant de l'utilisateur

**Réponse (200 OK):**
```json
{
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": "John Doe",
    "email": "john.doe@example.com",
    "role": "ROLE_ARTIST",
    "createdAt": "2024-01-15 10:30:00",
    "updatedAt": "2024-01-15 10:30:00"
  }
}
```

**Comportement particulier:** Tout utilisateur authentifié peut récupérer les informations d'un utilisateur.

---

### 3. Récupère un utilisateur par email

**Méthode:** `GET`
**URL:** `/api/users/email/{email}`
**Authentification:** Aucune (route publique)
**Paramètres:**
- `email` (string) - Email de l'utilisateur

**Réponse (200 OK):**
```json
{
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": "John Doe",
    "email": "john.doe@example.com",
    "role": "ROLE_ARTIST",
    "createdAt": "2024-01-15 10:30:00",
    "updatedAt": "2024-01-15 10:30:00"
  }
}
```

**Comportement particulier:** Cette route est publique et accessible sans authentification.

---

### 4. Créer un utilisateur

**Méthode:** `POST`
**URL:** `/api/users`
**Authentification:** ROLE_ADMIN
**Paramètres (Body JSON):**

```json
{
  "name": "New User",
  "email": "new.user@example.com",
  "password": "SecurePassword123!",
  "role": "ROLE_ARTIST"
}
```

**Validations:**
- `name` : Requis
- `email` : Requis, doit être un email valide
- `password` : Requis
- `role` : Requis, valeurs possibles: ROLE_USER, ROLE_ARTIST, ROLE_MODERATOR, ROLE_ADMIN

**Réponse (201 Created):**
```json
{
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440002",
    "name": "New User",
    "email": "new.user@example.com",
    "role": "ROLE_ARTIST",
    "createdAt": "2024-01-15 14:30:00",
    "updatedAt": "2024-01-15 14:30:00"
  }
}
```

**Comportement particulier:** Seuls les administrateurs peuvent créer des utilisateurs.

---

### 5. Modifier un utilisateur

**Méthode:** `PUT`
**URL:** `/api/users/{id}`
**Authentification:** ROLE_ADMIN ou propriétaire de la ressource
**Paramètres:**
- `id` (string, UUID) - Identifiant de l'utilisateur à modifier

**Body (JSON):**
```json
{
  "name": "Updated Name",
  "email": "updated.email@example.com",
  "role": "ROLE_MODERATOR"
}
```

**Réponse (200 OK):**
```json
{
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": "Updated Name",
    "email": "updated.email@example.com",
    "role": "ROLE_MODERATOR",
    "createdAt": "2024-01-15 10:30:00",
    "updatedAt": "2024-01-15 15:45:00"
  }
}
```

**Comportement particulier:**
- Les administrateurs peuvent modifier n'importe quel utilisateur.
- Un utilisateur peut modifier ses propres informations (vérifié par: `object.id == user.getId()`).
- Les utilisateurs non-admin ne peuvent généralement pas modifier leur propre rôle.

---

## Structure de données

### User
| Champ | Type | Description |
|-------|------|-------------|
| id | string (UUID) | Identifiant unique de l'utilisateur |
| name | string | Nom de l'utilisateur |
| email | string | Email de l'utilisateur |
| role | string | Rôle de l'utilisateur (ROLE_USER, ROLE_ARTIST, ROLE_MODERATOR, ROLE_ADMIN) |
| createdAt | string | Date de création (format: Y-m-d H:i:s) |
| updatedAt | string | Date de dernière modification (format: Y-m-d H:i:s) |

**Note:** Le mot de passe n'est jamais retourné dans les réponses.

## Rôles disponibles

- **ROLE_USER:** Utilisateur standard
- **ROLE_ARTIST:** Artiste (peut créer des chansons et albums)
- **ROLE_MODERATOR:** Modérateur (peut gérer les catégories et tags)
- **ROLE_ADMIN:** Administrateur (accès complet)

## Codes d'erreur

- **400 Bad Request:** Données invalides (validation échouée)
- **401 Unauthorized:** Authentification requise
- **403 Forbidden:** Permissions insuffisantes
- **404 Not Found:** Ressource non trouvée
- **422 Unprocessable Entity:** Erreur de validation métier (ex: email déjà utilisé)
