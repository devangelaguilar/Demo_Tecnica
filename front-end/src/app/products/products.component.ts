import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

interface Product {
  id: number;
  name: string;
  price: number;
  brand?: string;
  created_at: string;
}

@Component({
  selector: 'app-products',
  standalone: false,
  templateUrl: './products.component.html',
  styleUrl: './products.component.scss'
})
export class ProductsComponent implements OnInit {
  products: Product[] = [];
  newProduct = { name: '', price: 0, brand: '' };
  editProduct: Product | null = null;

  constructor(private http: HttpClient) { }

  ngOnInit(): void {
    this.fetchProducts();
  }

  fetchProducts(): void {
    const token = localStorage.getItem('token');
    const headers = token
      ? new HttpHeaders({ Authorization: `Bearer ${token}` })
      : undefined;
    this.http.get<Product[]>('http://localhost:8000/api/products', { headers })
      .subscribe({
        next: (data) => this.products = data,
        error: (error) => console.error('Error fetching products', error)
      });
  }

  createProduct(): void {
    const token = localStorage.getItem('token');
    const headers = token
      ? new HttpHeaders({ Authorization: `Bearer ${token}` })
      : undefined;
    this.http.post<Product>('http://localhost:8000/api/products', this.newProduct, { headers })
      .subscribe({
        next: (product) => {
          this.products.push(product);
          this.newProduct = { name: '', price: 0, brand: '' };
          window.alert('Product successfully created');
          this.fetchProducts();
        },
        error: (error) => console.error('Error creating product', error)
      });
  }

  openEditModal(product: Product): void {
    this.editProduct = { ...product };
  }

  updateProduct(): void {
    if (!this.editProduct) return;
    const token = localStorage.getItem('token');
    const headers = token
      ? new HttpHeaders({ Authorization: `Bearer ${token}` })
      : undefined;
    const url = `http://localhost:8000/api/products/${this.editProduct.id}`;
    this.http.patch<Product>(url, this.editProduct, { headers })
      .subscribe({
        next: (updatedProduct) => {
          window.alert('Product successfully updated');
          this.editProduct = null;
          this.fetchProducts();
        },
        error: (error) => console.error('Error updating product', error)
      });
  }

  deleteProduct(id: number): void {
    const token = localStorage.getItem('token');
    const headers = token
      ? new HttpHeaders({ Authorization: `Bearer ${token}` })
      : undefined;
    const url = `http://localhost:8000/api/products/${id}`;
    this.http.delete(url, { headers })
      .subscribe({
        next: () => {
          window.alert('Product successfully deleted');
          this.fetchProducts();
        },
        error: (error) => console.error('Error deleting product', error)
      });
  }

  isAdmin(): boolean {
    return localStorage.getItem('role') === 'admin';
  }

  // Export products to PDF using jsPDF and autoTable
  exportToPDF(): void {
    const doc = new jsPDF('p', 'pt', 'a4');
    doc.text("Products Report", 40, 40);

    // Prepare table data
    const head = [['ID', 'Name', 'Price', 'Brand', 'Created']];
    const body = this.products.map(p => [
      p.id.toString(),
      p.name,
      p.price.toString(),
      p.brand || '',
      new Date(p.created_at).toLocaleString()
    ]);

    autoTable(doc, { head, body, startY: 60 });
    doc.save("products.pdf");
  }

  // Export products to Excel using the XLSX library
  exportToExcel(): void {
    const ws: XLSX.WorkSheet = XLSX.utils.json_to_sheet(this.products);
    const wb: XLSX.WorkBook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Products');
    XLSX.writeFile(wb, 'products.xlsx');
  }
}
