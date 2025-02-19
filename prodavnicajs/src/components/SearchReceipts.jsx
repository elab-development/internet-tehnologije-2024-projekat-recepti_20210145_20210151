import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom"; // Za uzimanje parametara iz URL-a
import { Link } from "react-router-dom";

const SearchReceipts = () => {
  const location = useLocation(); // Koristi useLocation za pristup URL-u
  const [receipts, setReceipts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Preuzimanje parametara iz URL-a
    const queryParams = new URLSearchParams(location.search);
    const tipJela = queryParams.get("tip_jela");

    console.log("Tip jela:", tipJela);

    // Pravimo URL za pretragu sa parametrima
    const url = `http://localhost:8000/api/recepti/pretraga?tip_jela=${tipJela}`;
    console.log("Poslati URL:", url);

    // Fetch podataka sa servera
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        console.log("Dobijeni podaci:", data);

        // Ako su podaci u formatu koji se očekuje
        if (Array.isArray(data.recepti)) {
          setReceipts(data.recepti);
        } else {
          console.error("Neočekivan format podataka:", data);
          setReceipts([]);
        }
      })
      .catch((error) => {
        console.error("Greška pri učitavanju recepata:", error);
        setReceipts([]);
      })
      .finally(() => {
        setLoading(false);
      });
  }, [location]);

  // Prikazivanje podataka
  return (
    <div className="receipts-container">
      <h2 className="title">Recepti - {new URLSearchParams(location.search).get("tip_jela")}</h2>
      {loading ? (
        <p>Učitavanje...</p>
      ) : receipts.length === 0 ? (
        <p className="no-recipes">Nema dostupnih recepata za ovaj tip jela.</p>
      ) : (
        <div className="recipes-grid">
          {receipts.map((receipt, index) => (
            <div key={receipt.id} className="recipe-card">
              <div className="recipe-image">
                <img
                  src={`https://picsum.photos/300/200?random=${index}`}
                  alt="Recept"
                />
              </div>
              <div className="recipe-info">
                <h3 className="recipe-title">{receipt.naziv}</h3>
                <p className="recipe-type">{receipt.tip_jela}</p>
                <p className="recipe-time">Vreme pripreme: {receipt.vreme_pripreme} min</p>
                <Link to={`/recepti/${receipt.id}`} className="view-button">
                  Prikaži više
                </Link>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default SearchReceipts;

