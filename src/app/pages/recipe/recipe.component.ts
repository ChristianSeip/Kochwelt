import {Component, Input, OnInit} from '@angular/core';
import {RecipeService} from "../../recipe.service";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-recipe',
  templateUrl: './recipe.component.html',
  styleUrls: ['./recipe.component.scss']
})
export class RecipeComponent implements OnInit {

  @Input() portions:number | string = 1;
  recipe: any = {};
  ingredients: any = [];

  constructor(private route: ActivatedRoute, private recipeService: RecipeService) { }

  ngOnInit(): void {
    this.route.params.subscribe((params) => {
      this.recipeService.getRecipeById(params['id']).subscribe((response: any) => {
        this.recipe = response;
        this.recipe.created = new Date(this.recipe.created * 1000).toLocaleDateString("en-GB");
        this.ingredients = JSON.parse(JSON.stringify(this.recipe.ingredients));
      });
    });
  }

  calcIngredients() {
    if(this.portions < 1) {
      this.portions = 1;
    }
    if (typeof this.portions === "string") {
      this.portions = parseInt(this.portions);
    }
    for(let i = 0; i < this.ingredients.length; i++) {
      if(this.ingredients[i].amount !== null) {
        this.ingredients[i].amount = this.recipe.ingredients[i].amount * this.portions;
      }
    }
  }

}
