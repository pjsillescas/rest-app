import type { Product } from './ProductApi';

type Props = {
	product: Product,
};

export default function ProductComp({ product }: Props)
{
	return (<div className="product">
		<div className="prod-id">{product.id}</div>
		<div className="prod-name">{product.name}</div>
	</div>);
}
