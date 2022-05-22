import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class RecipeService {

  private api = "https://cooking.seip.io/api/index.php?ressource=recipe&";

  constructor(private http: HttpClient) { }

  getRecipeById(id: number) {
    return this.http.get(`${this.api}action=get&id=${id}`);
  }
}
