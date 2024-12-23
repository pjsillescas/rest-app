export type Product = {
	id: number,
	name: string,
};

export default class ProductApi {
	private static baseUrl: string = "http://localhost:8080";

	public static getAllProducts(callback: (products: Product[]) => void)
	{
		fetch(`${ProductApi.baseUrl}/products`, {
			mode: "cors",
		}).then(response => {
			response.json().then((products: Product[]) => callback(products));
		});
	}

	public static getProduct(id: number, callback: (product: Product) => void)
	{
		fetch(`${ProductApi.baseUrl}/products/${id}`, {
			mode: "cors",
		}).then(response => {
			response.json().then(callback);
		});
	}

	public static addProduct(name: string, callback: (product: Product) => void)
	{
		fetch(`${ProductApi.baseUrl}/products`, {
			method: "POST",
			body: JSON.stringify({name}),
			mode: "cors",
		}).then(response => {
			response.json().then(callback);
		});
	}

	public static updateProduct(id: number, name: string, callback: (product: Product) => void)
	{
		fetch(`${ProductApi.baseUrl}/products`, {
			method: "PUT",
			body: JSON.stringify({id, name}),
			mode: "cors",
		}).then(response => {
			response.json().then(callback);
		});
	}
}