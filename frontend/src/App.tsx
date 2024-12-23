import './App.css';

import { useCallback, useEffect, useState } from 'react';
import type { Product } from './ProductApi';
import ProductApi from './ProductApi';
import ProductComp from './ProductComp';

type ActionType = "ADD" | "UPDATE" | "GET" | undefined;

function App() {
	const [products, setProducts] = useState<Product[]>([]);
	const [lastProduct, setLastProduct] = useState<Product | undefined>(undefined);

	const [action, setAction] = useState<ActionType>(undefined);
	
	const [id, setId] = useState<number>(0);
	const [name, setName] = useState<string>("");

	console.log(`(${id}, ${name})`);

	const loadProducts = () => ProductApi.getAllProducts((aProducts: Product[]) => setProducts(aProducts));

	useEffect(() => {
		loadProducts();
	}, []);

	const addProductClick = useCallback(() => {
		ProductApi.addProduct(name??"", (product: Product) => {
			setLastProduct(product);
			loadProducts();
		});
	}, []);
	
	const updateProductClick = useCallback(() => {
		ProductApi.addProduct(name, (product: Product) => {
			setLastProduct(product);
			loadProducts();
		});
	}, []);
	
	const getProductClick = useCallback(() => {
		console.log("getting product " + id);
		ProductApi.getProduct(id, (product: Product) => {
			setLastProduct(product);
		});
	}, []);

	return (
		<div className="App">
			<div className="buttons">
				<button className="button" onClick={addProductClick}>Add</button>
				<button className="button" onClick={updateProductClick}>Update</button>
				<button className="button" onClick={getProductClick}>Get</button>
			</div>
			<form>
				<label>Id</label> <input name="id" onChange={(e: any) => setId(parseInt(e.target.value))} />
				<label>Name</label> <input name="id" onChange={(e: any) => setName(e.target.value)} />
			</form>
			<div className="products">
				{products.map(product => <ProductComp product={product}/>)}
			</div>
			{!!lastProduct && <div className="lastProduct">
				<ProductComp product={lastProduct}/>
			</div>}
		</div>
	);
}

export default App;
