<div align='center'>

## System wieloagentowy

### Temat:
Doradca zakupu sprzętu komputerowego ( Tablet / Laptop / PC )

Aleksander Sinkowski
</div>
<div align="justify">

### Opis:
* Projekt powstał na potrzeby zajęć.
* W czym pisać? W Pythonie i Flasku.
* Całość będzie śmigała web'owo.
* System wyświetli listę pytań oraz gotowych odpowiedzi. 
* Po interakcji z użytkownikiem, zbierze informacje i ustali jaki zakres parametrów przydzielić agentom.

__Pomysł 1 (jeden typ agenta):__ Każde pytanie ustali jeden parametr priorytetowy dla dla jednego agenta, po czym agencie ruszą do poszukiwać. Po wybraniu sobie jakichś propozycji, agent zapamięta parametr (np. gtx 760), po którym będzie porównywał się do innych agentów, korygując tym samym swoje dane oraz uzupełniając niewiadome na zasadzie tworzenia zakresu. Przykładowo: agent nastawiony na sprzęt gameingowy przyjmie wartość energooszczędności, przez co będzie szukał wydajnej karty graficznej o niskim zapotrzebowaniu energii.

__Pomysł 2 (dwa typy agenta):__ Każde pytanie ustala jeden parametr priorytetowy dla jednego agenta, po czym agenci szukający zgłaszają się do agentów bazodanowych podając swój priorytet. Każdy agent szukający dostanie propozycję w odpowiedzi, a agent bazodanowy dopisze sobie do wskazówek priorytet agenta szukającego. Tym sposobem kolejni agenci szukający dostaną inne, bardziej precyzyjne wedle wytycznych użytkownika propozycje.

__Pomysł 3 (trzy typy agenta):__ Jak wyżej z tym, że będzie trzeci agent reprezentujący użytkownika rozdający parametry, po których agenci będą szukać propozycji. Po znalezieniu propozycji wrócą i zdadzą raport trzeciemu, gdzie ten wprowadzi modyfikację różne dla każdego agenta i wyśle ich raz jeszcze na poszukiwania optymalnych propozycji. 

:warning: __Pomysł 4 (Tak powinno być):__ Na podstawie wybranych opcji agencie dostaną te same parametry i ruszą do agenta odpowiedzialnego za bazę danych. Agent bazodanowy będzie zmieniał swoje parametry tak by miał jak największy zyst z transakcji. Np. nauczył się, że agenci szukają sprzętu gameingowego więc próbuje wcisnąć droższy lub słabszy jak mu zalega na magazynie.

</div>