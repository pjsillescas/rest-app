import { useCallback, useState } from "react";

type Props = {
	addCallback: (name: string) => void,
	updateCallback: (id: number, name: string) => void,
	getCallback: (id: number) => void,
};

export default function ActionForm({ addCallback, updateCallback, getCallback }: Props) {
	const [productId, setProductId] = useState<number>(0);
	const [productName, setProductName] = useState<string>("");

	const addProductClick = useCallback(() => {
		addCallback(productName);
	}, [productName]);
	
	const updateProductClick = useCallback(() => {
		updateCallback(productId, productName);
	}, [productId, productName]);
	
	const getProductClick = useCallback(() => {
		getCallback(productId);
	}, [productId]);

	console.log(`(${productId}, ${productName})`);

	return (<>
		<div className="buttons">
			<button className="button" onClick={addProductClick}>Add</button>
			<button className="button" onClick={updateProductClick}>Update</button>
			<button className="button" onClick={getProductClick}>Get</button>
		</div>
		<form>
			<label>Id</label> <input name="productId" onChange={(e: any) => setProductId(parseInt(e.target.value))} />
			<label>Name</label> <input name="id" onChange={(e: any) => setProductName(e.target.value)} />
		</form>
	</>);
}
