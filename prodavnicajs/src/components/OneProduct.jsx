/*import React from 'react'

function OneProduct() {
  return (
    <div className = "card">
      <img className = "card-img" src="https:/picsum.photos/200" alt="Slika" />
      <div className="card-body">
        <h3 className="card-title">Product name</h3>
        <p className="card-text">Description of the product.</p>
      </div>
      <button className="btn">+</button>
      <button className="btn">-</button>
    </div>
  )
}

export default OneProduct */

import React from 'react';

function OneProduct({ product }) {
    return (
        <div className="card">
            <img className="card-img" src={product.slika || "https:/picsum.photos/200"} alt={product.naziv} />
            <div className="card-body">
                <h3 className="card-title">{product.naziv}</h3>
                <p className="card-text">{product.kategorija}</p>
                <p className="card-text">{product.tip}</p>
                {/*<p className="card-text">{product.dostupna_kolicina}</p>*/}
                <p className="card-price">{product.cena} RSD</p>
            </div>
            <button className="btn">Dodaj u korpu</button>
        </div>
    );
}

export default OneProduct;
