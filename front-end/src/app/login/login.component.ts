import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  standalone: false,
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {

  email: string = "";
  password: string = "";

  constructor(private router: Router, private http: HttpClient) { }

  ngOnInit(): void {

  }

  login() {
    let bodyData = {
      "email": this.email,
      "password": this.password
    }
    this.http.post('http://localhost:8000/api/login', bodyData).subscribe((data: any) => {
      console.log(data);

      if (data) {
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
        localStorage.setItem('role', data.user.role);
        this.router.navigateByUrl('/products');
      }

    });
  }

  save() {
    this.login();
  }

}
