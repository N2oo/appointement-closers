# Application visant à apprendre par la pratique les rudiments du Test Driven Developpement (TDD)

## A quoi sert cette application ?
Afin de mettre en pratique le TDD, j'ai décidé de mettre en place une application relative à la gestion par proximité des dates.

La notion algorithmique sous-jacente est le [clustering / partionnement](https://www.actuia.com/faq/quest-ce-que-le-clustering-et-comment-le-mettre-en-oeuvre/) de données. (K-means, Mean-Shift, Propagation d'affinité, Optics ...)

Pour des raisons d'apprentissage, aucun algorithme issu d'une librairie n'est utilisé, dans le cadre d'une utilisation en production, et en toute proportion avec les besoins exprimés, il serait préférable d'utiliser une implémentation issue de librairie / package dédié(e).

### Use Case sample
[Fr]
Vous êtes un professionnel de la santé, et lorsque vous consultez la fiche de votre patient, vous vous rendez compte que plusieurs rendez vous sont planifiés à des échéances très rapprochées.

Vous estimez que le fait mutualiser ces rendez-vous n'aura pas d'incidence :
vous rapprochez les dates manuellement et vous réalisez que ce cas ne doit pas être isolé, et que vous passez plus de temps à évaluer les dates à rapprocher qu'à réélement décider de les rapprocher. Vous souhaiteriez que des suggestions vous soient faites et que vous puissiez valider, ou invalider les rapprochements calculés.

### Solution 

[Fr] Le service appointementCloser (et plus précisément `App\Service\Closer`) attends les différentes dates (`$dateTimeList`), votre limite de rapprochement (`int $offset` + `App\Service\Enumeration\CloserArguments $unity`) et vous retourne un tableau contenant les différentes dates soumises rapprochées dans le respect de votre critère de rapprochement.

Charge à vous de les intégrer dans votre système pour l'interogation, l'appréciation des suggestions ou leur validation.