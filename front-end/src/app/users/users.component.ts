import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  phone?: string;
  photo?: string;
  created_at: string;
}

@Component({
  selector: 'app-users',
  standalone: false,
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {
  users: User[] = [];
  newUser: User = { id: 0, name: '', email: '', phone: '', role: 'user', photo: '', created_at: '' };
  editUser: User | null = null;
  infoUser: User | null = null;
  newUserFile: File | null = null;
  selectedFile: File | null = null;

  constructor(private http: HttpClient) { }

  ngOnInit(): void {
    this.fetchUsers();
  }

  fetchUsers(): void {
    const token = localStorage.getItem('token');
    const headers = token ? new HttpHeaders({ Authorization: `Bearer ${token}` }) : undefined;
    this.http.get<User[]>('http://localhost:8000/api/users', { headers })
      .subscribe({
        next: (data) => this.users = data,
        error: (error) => console.error('Error fetching users', error)
      });
  }

  isAdmin(): boolean {
    return localStorage.getItem('role') === 'admin';
  }

  exportToPDF(): void {
    const doc = new jsPDF('p', 'pt', 'a4');
    doc.text("Reporte de usuarios", 40, 40);
    const head = [['ID', 'Name', 'Email', 'Role', 'Created']];
    const body = this.users.map(u => [
      u.id.toString(),
      u.name,
      u.email,
      u.role,
      new Date(u.created_at).toLocaleString()
    ]);
    autoTable(doc, { head, body, startY: 60 });
    doc.save("users.pdf");
  }

  exportToExcel(): void {
    const ws: XLSX.WorkSheet = XLSX.utils.json_to_sheet(this.users);
    const wb: XLSX.WorkBook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Users');
    XLSX.writeFile(wb, 'users.xlsx');
  }

  onFileChange(event: any): void {
    const file: File = event.target.files[0];
    if (file) {
      this.selectedFile = file;
    }
  }

  onNewUserFileChange(event: any): void {
    const file: File = event.target.files[0];
    if (file) {
      this.newUserFile = file;
    }
  }

  openInfoModal(user: User): void {
    this.infoUser = user;
  }

  openEditModal(user: User): void {
    this.editUser = { ...user };
    this.selectedFile = null;
  }

  openAddUserModal(): void {
    this.newUser = { id: 0, name: '', email: '', phone: '', role: 'user', photo: '', created_at: '' };
    this.newUserFile = null;
  }

  createUser(): void {
    const token = localStorage.getItem('token');
    const headers = token ? new HttpHeaders({ Authorization: `Bearer ${token}` }) : undefined;
    const url = `http://localhost:8000/api/register`;
    const formData = new FormData();
    formData.append('name', this.newUser.name);
    formData.append('email', this.newUser.email);
    formData.append('password', 'password');
    formData.append('phone', this.newUser.phone || '');
    formData.append('role', this.newUser.role);
    if (this.newUserFile) {
      formData.append('photo', this.newUserFile);
    }
    this.http.post<User>(url, formData, { headers })
      .subscribe({
        next: (createdUser) => {
          window.alert('Usuario creado de forma correcta');
          this.fetchUsers();
        },
        error: (error) => console.error('Error creating user', error)
      });
  }

  updateUser(): void {
    if (!this.editUser) return;
    const token = localStorage.getItem('token');
    const headers = token ? new HttpHeaders({ Authorization: `Bearer ${token}` }) : undefined;
    const url = `http://localhost:8000/api/users/${this.editUser.id}`;
    const formData = new FormData();
    formData.append('name', this.editUser.name);
    formData.append('email', this.editUser.email);
    formData.append('role', this.editUser.role);
    formData.append('phone', this.editUser.phone || '');
    if (this.selectedFile) {
      formData.append('photo', this.selectedFile);
    }
    this.http.patch<User>(url, formData, { headers })
      .subscribe({
        next: (updatedUser) => {
          window.alert('Usuario actualizado');
          this.editUser = null;
          this.selectedFile = null;
          this.fetchUsers();
        },
        error: (error) => console.error('Error updating user', error)
      });
  }

  deleteUser(id: number): void {
    const token = localStorage.getItem('token');
    const headers = token ? new HttpHeaders({ Authorization: `Bearer ${token}` }) : undefined;
    const url = `http://localhost:8000/api/users/${id}`;
    this.http.delete(url, { headers })
      .subscribe({
        next: () => {
          window.alert('Usuario eliminado');
          this.fetchUsers();
        },
        error: (error) => console.error('Error deleting user', error)
      });
  }
}
