import { Component, OnInit } from '@angular/core';
import {RecipeService} from "../../recipe.service";

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.scss']
})
export class IndexComponent implements OnInit {

  spotlight: any = {};
  recommended: any = [];

  constructor(private recipeService: RecipeService) { }

  ngOnInit(): void {
    this.getSpotlightRecipe();
    this.getRecommendations();
  }

  getSpotlightRecipe() {
    let r = Math.floor(Math.random() * (4 - 1) + 1);
    this.recipeService.getRecipeById(r).subscribe((response: any) => this.spotlight = response);
  }

  getRecommendations() {
    for(let i = 1; i < 4; i++) {
      this.recipeService.getRecipeById(i).subscribe((response: any) => this.recommended.push(response));
    }
  }
}
