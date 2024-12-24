import './App.css';

import { useCallback, useEffect, useState } from 'react';
import type { Product } from './ProductApi';
import ProductApi from './ProductApi';
import ProductComp from './ProductComp';
import ActionForm from './ActionForm';

export default function App() {
	const [products, setProducts] = useState<Product[]>([]);
	const [lastProduct, setLastProduct] = useState<Product | undefined>(undefined);

	const loadProducts = () => ProductApi.getAllProducts((aProducts: Product[]) => setProducts(aProducts));

	useEffect(() => {
		loadProducts();
	}, []);

	const addCallback = useCallback((name: string) => {
		ProductApi.addProduct(name??"", (product: Product) => {
			setLastProduct(product);
			loadProducts();
		});
	}, [setLastProduct]);
	
	const updateCallback = useCallback((id: number, name: string) => {
		console.log(`update product '${id}' '${name}'`);
		ProductApi.updateProduct(id, name, (product: Product) => {
			setLastProduct(product);
			loadProducts();
		});
	}, [setLastProduct]);
	
	const getCallback = useCallback((id: number) => {
		ProductApi.getProduct(id, (product: Product) => {
			setLastProduct(product);
		});
	}, [setLastProduct]);

	return (
		<div className="App">
			<ActionForm getCallback={getCallback} updateCallback={updateCallback} addCallback={addCallback} />
			<div className="products">
				{products.map(product => <ProductComp product={product}/>)}
			</div>
			{!!lastProduct && <div className="last-product">
				<ProductComp product={lastProduct}/>
			</div>}
		</div>
	);
}
