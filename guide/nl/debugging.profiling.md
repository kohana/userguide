# Profiling

Kohana biedt een zeer eenvoudige manier aan om statistieken over uw aanvraag te tonen:

1. Gewone [Kohana] methodes dat aangeroepen worden
2. Requests
3. [Database] queries
4. Gemiddelde uitvoeringstijden voor uw applicatie

## Voorbeeld

Je kan op elk tijdstip de huidige [profiler] statistieken tonen of opvragen:

    <div id="kohana-profiler">
    <?php echo View::factory('profiler/stats') ?>
    </div>

## Voorbeeld

{{profiler/stats}}