import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import Dashboard from './admin/Dashboard';
import ProductsTable from './admin/ProductsTable';
import CategoriesTable from './admin/CategoriesTable';

const screens = {
    overview: Dashboard,
    products: ProductsTable,
    categories: CategoriesTable,
};

const container = document.getElementById('admin-content');
const Screen = container ? screens[container.dataset.screen] : null;

if (container && Screen) {
    createRoot(container).render(
        <StrictMode>
            <Screen />
        </StrictMode>,
    );
}
