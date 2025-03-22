import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { error } from 'console';

@Component({
  selector: 'app-register',
  standalone: false,
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent {
  name: string = "";
  role: string = "";
  photo: string = "";
  email: string = "";
  phone: string = "";
  password: string = "";

  constructor(private router: Router, private http: HttpClient) { }

  file: File | null = null;

  onFileChange(event: any): void {
    this.file = event.target.files[0];
    if (this.file) {

    }
  }

  register() {
    if (this.file) {
      const formData = new FormData();
      formData.append('name', this.name);
      formData.append('role', this.role);
      formData.append('email', this.email);
      formData.append('phone', this.phone);
      formData.append('password', this.password);
      formData.append('photo', this.file);

      this.http.post('http://localhost:8000/api/register', formData).subscribe((data: any) => {
        console.log(data);

        if (data) {
          this.router.navigateByUrl('/products');
        }

      });
    }
  }

}
