# Validation

La validation peut être effectuée sur tous les tableaux en utilisant la classe [Validate]. Les labels, filtres, règles et callbacks peuvent être attachés à un objet Validate via un tableau de clé, appellées "champs" (field name).

labels
:  Un label est la version lisible (par un humain) d'un nom de champ.

filters
:  Un filtre modifie la valeur d'un champs avant que les règles et callbacks ne soient exécutées.

rules
:  Une règle est une vérification sur un champ qui retourne `TRUE` ou `FALSE`. Si une règle retourne `FALSE`, une erreur sera ajoutée à ce champ.

callbacks
:  Une callback est une méthode spécifique ayant accès à l'ensemble de l'objet Validate.
   La valeur retournée par une callback est ignorée. A la place, en cas d'erreur une callback doit manuellement ajouter une erreur à un champ en utilisant [Validate::error].

[!!] A noter que les callbacks [Validate] et les [callbacks PHP](http://php.net/manual/language.pseudo-types.php#language.types.callback) ne sont pas pareils.

Utiliser `TRUE` comment nom de champ lors de l'ajout d'un filtre, règle ou callback a pour effet de l'appliquer à tous les champs.

**L'objet [Validate] supprimera tous les champs du tableau qui n'ont pas explicitement été utilisés via un label, un filtre, une règle ou une callback. Ceci pour empêcher tout accès à un champ qui n'a pas été validé et ajouter ainsi une protection de sécurité supplémentaire.**

La création d'un objet de validation est faite en utilsiant la méthode [Validate::factory]:

    $post = Validate::factory($_POST);

[!!] L'objet `$post` sera utilisé pour le reste de ce tutorial dans lequel sera illustré la validation de l'inscription d'un nouveal utilisateur.

### Règles par défaut

La validation supporte les règles par défaut suivantes:

Nom de la règle           | Description
------------------------- |-------------------------------------------------
[Validate::not_empty]     | La valeur ne doit pas être vide
[Validate::regex]         | La valeur respecte l'expression réguliére spécifiée
[Validate::min_length]    | La valeur respecte un nombre minimum de caractères
[Validate::max_length]    | La valeur respecte un nombre maximum de caractères
[Validate::exact_length]  | La valeur fait exactement le nombre de caractéres spécifiés
[Validate::email]         | La valeur doit respecter un format d'email
[Validate::email_domain]  | Le domaine de l'email existe
[Validate::url]           | La valeur entrée doit respecter un format d'URL
[Validate::ip]            | La valeur entrée doit respecter un format d'adresse IP
[Validate::phone]         | La valeur entrée doit respecter un format d'un uméro de téléphone
[Validate::credit_card]   | La valeur entrée doit respecter un format de numéro de carte de crédit
[Validate::date]          | La valeur entrée doit respecter un format de date (et heure)
[Validate::alpha]         | Seuls les caractères alphabétiques sont autorisés
[Validate::alpha_dash]    | Seuls les caractères alphabétiques et le caractère tiret '-' sont autorisés
[Validate::alpha_numeric] | Seuls les caractères alphabétiques et numériques sont autorisés
[Validate::digit]         | La valeur doit être un chiffre
[Validate::decimal]       | La valeur doit être décimale ou flottante
[Validate::numeric]       | Seuls les caractères numériques sont autorisés
[Validate::range]         | La valeur doit être dans l'intervalle spécifié
[Validate::color]         | La valeur entrée doit respecter un format de couleur hexadécimal
[Validate::matches]       | La valeur doit correspondre à la valeur d'un autre champ

[!!] Toute méthode existante de la classe [Validate] peut être utilisée directement sans utiliser une déclaration de callback compléte. Par exemple, ajouter la règle `'not_empty'` est la même chose que `array('Validate', 'not_empty')`.

## Ajouter des filtres

Tous les filtres de validation sont définis par un nom de champ, une méthode ou une fonction (en utilisant la syntaxe des [callbacks PHP](http://php.net/manual/language.pseudo-types.php#language.types.callback)), ainsi q'un tableau de paramètres:

    $object->filter($field, $callback, $parameter);

Les filtres modifient la valeur d'un filtre avant leur vérification par les règles et callbacks définies.

Par exemple pour convertir un nom d'utilisateur en minuscule, alors il suffit d'écrire:

    $post->filter('username', 'strtolower');

Autre exemple, si l'on souhaite enlever les caratères vides au début et en fin de chaine de tous les champs, alors il faut écrire:

    $post->filter(TRUE, 'trim');

## Ajouter des règles

Toutes les règles de validation sont définies par un nom de champ, une méthode ou une fonction (en utilisant la syntaxe des [callbacks PHP](http://php.net/manual/language.pseudo-types.php#language.types.callback)), ainsi q'un tableau de paramètres:

    $object->rule($field, $callback, $parameter);

Pour commencer notre exemple, nous allons commencer par valider le tableau `$_POST` contenant des informations d'inscription d'un utilisateur.

Pour cela nous avons besoin de traiter les informations POSTées en utilisant [Validate]. Commencons par ajouter quelque régles:

    $post
        ->rule('username', 'not_empty')
        ->rule('username', 'regex', array('/^[a-z_.]++$/iD'))

        ->rule('password', 'not_empty')
        ->rule('password', 'min_length', array('6'))
        ->rule('confirm',  'matches', array('password'))

        ->rule('use_ssl', 'not_empty');

Toute fonction PHP existante peut aussi être utilisée comme une règle. Par exemple si l'on souhaite vérifier que l'utilisateur a entré une valeur correcte pour une question, on peut écrire:

    $post->rule('use_ssl', 'in_array', array(array('yes', 'no')));

A noter que les tableaux de paramètres doivent quand même être insérés dans un tableau! Si vous ne mettez pas ce tableau, `in_array` serait appelée  via `in_array($value, 'yes', 'no')`, ce qui aboutirait à une erreur PHP.

Toute régle spécifique peut être ajoutée en utilisant une [callback PHP](http://php.net/manual/language.pseudo-types.php#language.types.callback):

    $post->rule('username', array($model, 'unique_username'));

La méthode `$model->unique_username()` ressemblerait alors à:

    public function unique_username($username)
    {
        // Vérifie si le nom d'utilisateur existe déjà dans la base de données
        return ! DB::select(array(DB::expr('COUNT(username)'), 'total'))
            ->from('users')
            ->where('username', '=', $username)
            ->execute()
            ->get('total');
    }

[!!] Vous pouvez définir vos propres régles pour faire des vérifications additionnelles. Ces régles peuvent être réutilisés à plusieurs fins. Ces méthodes vont presque toujours exister au sein d'un modèle mais peuvent être définies dans nimporte quelle classe.

## Ajouter des callbacks

Toutes les callbacks de validation sont définies par un nom de champ et une méthode ou une fonction (en utilisant la syntaxe des [callbacks PHP](http://php.net/manual/language.pseudo-types.php#language.types.callback)):

    $object->callback($field, $callback);

[!!] Contrairement aux filtres et aux régles, aucun paramètre n'est passé à une callback.

Le mot de passe utilisateur doit être hashé parès validaiton, nous allons donc le faire avec une callback:

    $post->callback('password', array($model, 'hash_password'));

Cela implique la création de la méthode `$model->hash_password()` de la manière suivante:

    public function hash_password(Validate $array, $field)
    {
        if ($array[$field])
        {
            // Hasher le mot de passe s'il existe
            $array[$field] = sha1($array[$field]);
        }
    }

# Un exemple complet

TOut d'abord nous avons besoin d'une [Vue] contenant le formulaire HTML que l'on placera dans `application/views/user/register.php`:

    <?php echo Form::open() ?>
    <?php if ($errors): ?>
    <p class="message">Des erreurs ont été trouvées, veuillez vérifier les informations entrées.</p>
    <ul class="errors">
    <?php foreach ($errors as $message): ?>
        <li><?php echo $message ?></li>
    <?php endforeach ?>
    <?php endif ?>

    <dl>
        <dt><?php echo Form::label('username', 'Nom d'utilisateur') ?></dt>
        <dd><?php echo Form::input('username', $post['username']) ?></dd>

        <dt><?php echo Form::label('password', 'Mot de passe') ?></dt>
        <dd><?php echo From::password('password') ?></dd>
        <dd class="help">Le mot de passe doit contenir au moins 6 caractères.</dd>
        <dt><?php echo Form::label('confirm', 'Confirmer le mot de passe') ?></dt>
        <dd><?php echo Form::password('confirm') ?></dd>

        <dt><?php echo Form::label('use_ssl', 'Utiliser une sécurité supplémentaire?') ?></dt>
        <dd><?php echo Form::select('use_ssl', array('yes' => 'Toujours', 'no' => 'Seulement si nécessaire'), $post['use_ssl']) ?></dd>
        <dd class="help">Pour des raisons de sécurité, SSL est toujours utilisé pour les paiements.</dd>
    </dl>

    <?php echo Form::submit(NULL, 'S\'inscrire') ?>
    <?php echo Form::close() ?>

[!!] Cette exemple utilise le helper [Form]. L'utiliser au lieu d'écrire du code HTML vous assure que tous les objets du formulaire vont traiter correctement les caractères HTML. Si vous souhaitez écrire le code HTML directement, veillez à utiliser [HTML::chars] pour filtrer/échaper les informations entrées par les utilisateurs.

Ensuite nous avons besoin d'un controleur et d'une action pour traiter l'inscription des utilisaeurs, fichier qu'on placera dans `application/classes/controller/user.php`:

    class Controller_User extends Controller {

        public function action_register()
        {
            $user = Model::factory('user');

            $post = Validate::factory($_POST)
                ->filter(TRUE, 'trim')

                ->filter('username', 'strtolower')

                ->rule('username', 'not_empty')
                ->rule('username', 'regex', array('/^[a-z_.]++$/iD'))
                ->rule('username', array($user, 'unique_username'))

                ->rule('password', 'not_empty')
                ->rule('password', 'min_length', array('6'))
                ->rule('confirm',  'matches', array('password'))

                ->rule('use_ssl', 'not_empty')
                ->rule('use_ssl', 'in_array', array(array('yes', 'no')))

                ->callback('password', array($user, 'hash_password'));

            if ($post->check())
            {
                // Les données ont éta validées, on inscrit l'utilisateur
                $user->register($post);

                // Toujours rediriger l'utilisateur après une validation de formulaire réussie afin de ne pas avoir les avertissement de rafraichissement
                $this->request->redirect('user/profile');
            }

            // La validation a échoué, récupérons les erreurs
            $errors = $post->errors('user');

            // Affiche le formulaire d'inscription
            $this->request->response = View::factory('user/register')
                ->bind('post', $post)
                ->bind('errors', $errors);
        }

    }

Nous avons aussi besoin d'un modèle qui sera placé dans `application/classes/model/user.php`:

    class Model_User extends Model {

        public function register($array)
        {
            // Créé un nouvel utilisateur dans la base de données
            $id = DB::insert(array_keys($array))
                ->values($array)
                ->execute();

            // Sauvegarde l'identifiant de l'utilisateur dans un cookie
            cookie::set('user', $id);

            return $id;
        }

    }

C'est tout! Nous avons désormais un formulaire d'inscritpion opérationnel et qui vérifie les informations entrées.