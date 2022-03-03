import React from 'react';
import View from '../View/View';
import styles from './AgreementView.scss';

const AgreementView: React.FC = () => {

    return (
        <View
            title="Regulamin i polityka prywatności"
            withBackground
            className={styles.agreementView}
        >
            <h3>1. Kolekcjonowanie danych</h3>
Serwis zbiera i przechowuje dane zarejestrowanych użytkowników takie jak: imię, nazwisko, nazwa firmy, zdjęcie profilowe, adres email, numer telefonu kontaktowego, data urodzenia, płeć, wskazana przez użytkownika lokalizacja, link do profilu w serwisie Facebook, link do profilu w serwisie Instagram, udostępniony adres strony internetowej, hasło dostępu. Wszystkie z wymienionych danych są bezpiecznie szyfrowane i będą wykorzystywane w celu identyfikacji użytkownika, a także umożliwienia mu tworzenia nowej zawartości w serwisie. Niezależnie od roli użytkownika w serwisie, w tym gość zbierane są dane dotyczące adresu ip, z którego zostało dokonane połączenie oraz unikalnego tokena przechowywanego w plikach cookies, aby zapewnić najwyższy poziom bezpieczeństwa, a także płynne działanie serwisu.
            <h3>2. Wykorzystanie zebranych danych</h3>
Zgromadzone dane mogą zostać w przyszłości wykorzystane w celu rozwijania dodatkowych usług w serwisie, w tym w celach marketingowych.
            <h3>3. Ochrona danych</h3>
Dane przechowujemy w zabezpieczonej bazie danych, niedostępnej dla osób trzecich.
            <h3>4. Modyfikacja i usuwanie danych</h3>
W celu zmiany danych, użytkownik po zalogowaniu może dokonać zmiany istniejących danych. W przypadku chęci usunięcia konta prosimy o kontakt na adres email podany w punkcie 8.
            <h3>5. Kontakt z użytkownikiem</h3>
Kontakt z użytkownikiem odbywa się drogą mailową lub telefoniczną.
            <h3>6. Wyrażenie zgody przez użytkownika</h3>
Korzystając z usług dostarczanych przez nasz serwis akceptujesz ten Regulamin i Politykę Prywatności, które mogą zostać zmienione w późniejszym czasie. Na tej stronie zawsze znajduje się obowiązująca wersja tego dokumentu.
            <h3>7. Treści utworzone przez użytkowników</h3>
Nie ponosimy odpowiedzialności za treści utworzone przez użytkowników. Jeśli uważasz, że pewna zawartość narusza regulamin, prosimy o kontakt na adres email podany w punkcie 8. Wszelkie nazwy podawane przez użytkowników są uznane za publiczne, a odpowiedzialność za ich podanie ponosi użytkownik. W przypadku danych przesłanych anonimowo, które łamią obowiązujące prawo, zastrzegamy sobie możlwość usunięcia takich danych bez wcześniejszego ostrzeżenia.
            <h3>8. Kontakt</h3>
W razie pytań lub wątpliwości należy skontaktować się z nami pod adresem: kontakt@zagra.com.
        </View>
    );
};

export default AgreementView;