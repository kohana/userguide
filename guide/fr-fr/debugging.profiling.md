# Profiling

Kohana fournit de façon très facile les statistiques de vos applications:

1. Appels de méthodes [Kohana] communes
2. Requêtes URI
3. Requêtes de [base de données](tutorials.databases)
4. Temps moyen d'execution de votre application

## Affichage/Récupération des statistiques

Vous pouvez afficher ou récupérer les statistiques courantes à tout moment en faisant:

~~~
<div id="kohana-profiler">
<?php echo View::factory('profiler/stats') ?>
</div>
~~~

## Exemple

{{profiler/stats}}