# Projekat
Ovaj projekat je rađen kao deo obaveza na Fakultetu [Fakultet organizacionih nauka] u okviru predmeta Internet tehnologije.

# Inteligentna Web Prodavnica Hrane

Ovaj projekat predstavlja web aplikaciju za inteligentnu kupovinu i planiranje ishrane. Aplikacija omogućava korisnicima da istražuju recepte, dobiju listu potrebnih namirnica za izabrana jela i kupuju proizvode putem jednostavnog interfejsa.

## Funkcionalnosti

### Gost
- Prikaz svih dostupnih proizvoda i recepata.
- Osnovna pretraga i filtriranje po kategorijama.
- Nema mogućnost kupovine bez registracije.

### Registrovani korisnik
- Unos sastojaka koje već ima i dobijanje predloga recepata.
- Pregled i odabir recepata, automatsko generisanje liste potrebnih proizvoda.
- Dodavanje proizvoda u korpu, izmena količina i potvrda kupovine.
- Pretraga po nazivu, filteri po kategoriji, ceni i dostupnosti.
- Paginacija proizvoda (npr. 9 po stranici).

### Administrator
- Dodavanje, izmena i brisanje proizvoda.
- Upravljanje receptima i vezivanje proizvoda za recepte.
- Uvid u listu obavljenih kupovina sa detaljima korisnika i ukupnim iznosima.

## Tehnologije

- **Frontend:** HTML, CSS, JavaScript, React
- **Backend:** PHP (Laravel)
- **Baza podataka:** MySQL
- **Razvojno okruženje:** XAMPP

## Pokretanje aplikacije

1. Klonirati repozitorijum:
   ```bash
   git clone git@github.com:isidora168/internet-tehnologije-2024-projekat-recepti_20210145_20210151.git
2. Postaviti fajlove u htdocs folder unutar XAMPP-a.
3. Pokrenuti Apache i MySQL preko XAMPP Control Panel-a.
4. U phpMyAdmin kreirati praznu bazu podataka sa nazivom prodavnica_app, zatim odabrati karticu Import, učitati SQL fajl iz repozitorijuma i kliknuti na dugme Go kako bi se učitali podaci.
5. Otvoriti aplikaciju u pregledaču: http://localhost/internet-tehnologije-2024-projekat-recepti_20210145_20210151/

## 📂 Dokumentacija
- [Frontend dokumentacija](prodavnicajs/README.md)
- [Backend dokumentacija](projekat/prodavnica_app/README.md)
