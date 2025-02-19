

import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

const OneReceipt = () => {
  const { id } = useParams();
  const [receipt, setReceipt] = useState(null);

  useEffect(() => {
    fetch(`http://localhost:8000/api/recepti/${id}`)
      .then((response) => response.json())
      .then((data) => setReceipt(data))
      .catch((error) => console.error("Greška pri učitavanju recepta:", error));
  }, [id]);

  if (!receipt) return <p className="loading">Učitavanje...</p>;

  return (
    <div className="one-receipt-container">
      <img
        className="one-receipt-image"
        src={`https://picsum.photos/600/400?random=${id}`}
        alt="Recept"
      />
      <div className="one-receipt-content">
        <h2 className="one-receipt-title">{receipt.naziv}</h2>
        <p className="one-receipt-type">{receipt.tip_jela}</p>
        <p className="one-receipt-time">Vreme pripreme: {receipt.vreme_pripreme} min</p>
        <h3 className="one-receipt-subtitle">Opis pripreme:</h3>
        <p className="one-receipt-description">{receipt.opis_pripreme}</p>
      </div>
    </div>
  );
};

export default OneReceipt;
