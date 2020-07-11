# Self-Hosted Google Fonts
Forked by Guido De Gobbis
Original von @asadkn siehe https://github.com/asadkn/selfhost-google-fonts

### Neuerungen:

- Google Fonts Liste aktualisiert

- Bei Aktivierung des Plugins wird es automatisch auch in den Einstellungen aktiviert.
Alle Process-Einstellungen sind standardmäßig aktiviert.

- Bei Multisites wird das Plugin immer netzwerkweit aktiviert und auch für jede Site in den Einstellungen aktiviert.
Alle Process-Einstellungen sind standardmäßig aktiviert.

- Der Fonts-Cache-Ordner ist nun immer der gleiche, auch bei Multisites.
Damit soll verhindert werden, dass pro Site die selbe Font mehrfach gespeichert wird.

- Beim löschen des Font-Caches einer Seite werden jetzt auch die gecachten Dateien aus dem Cache-Ordner gelöscht.
Der Gedanke dahinter ist, wenn ich den Cache lösche, dann vermutlich weil ich auch andere Schriften verwende.
Bei der Löschung in Multisites werden keine Fonts gelöscht, die in einer der anderen Sites verwendet werden.

- Der [Fix](https://wordpress.org/support/topic/fix-for-checkboxes-not-reflecting-current-values/) von @ps07 wurde ebenfalls berücksichtigt.

- Eigener Updateserver, da nicht auf Wordpress veröffentlicht.
