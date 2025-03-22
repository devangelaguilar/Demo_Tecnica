import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

interface User {
  id: number;
  name: string;
  email: string;
  phone: string;
  role: string;
  photo: string;
  created_at: string;
}

@Component({
  selector: 'app-profile',
  standalone: false,
  templateUrl: './profile.component.html',
  styleUrl: './profile.component.scss'
})
export class ProfileComponent implements OnInit {
  user: User = {} as User;

  constructor(private http: HttpClient) { }

  ngOnInit(): void {
    this.getProfile();
  }

  getProfile(): void {
    const token = localStorage.getItem('token');
    const headers = token ? new HttpHeaders({ Authorization: `Bearer ${token}` }) : undefined;

    this.http.get<User>('http://localhost:8000/api/me', { headers })
      .subscribe({
        next: (data) => this.user = data,
        error: (error) => console.error('Error fetching profile', error)
      });
  }
}
