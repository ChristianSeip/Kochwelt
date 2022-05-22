import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class ContactService {

  private api = "https://cooking.seip.io/api/index.php?ressource=contact&";

  constructor(private http: HttpClient) { }

  addContact(name: string, mail: string, message: string) {
    const headers = new Headers();
    headers.append('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    let body = JSON.stringify({name: name, mail: mail, msg: message});
    return this.http.post(`${this.api}action=add`, body);
  }
}
