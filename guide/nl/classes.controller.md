#Controller

Je controllers moeten deze klasse extenden

##Eigenschappen

###request

Dit zal de request instantie opslaan voor het wordt doorgegeven aan de constructor.

##Functies

###__construct()

De parameter is een request instantie [Request](classes.request)

Deze functie laat toe de request instantie te gebruiken als

    $this->request

###before()

Dit wordt uitgevoerd voor de <code>action_</code> functie in je controller.

###after()

Dit wordt uitgevoerd na de <code>action_</code> functie in je controller.