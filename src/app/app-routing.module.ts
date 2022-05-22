import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {IndexComponent} from "./pages/index/index.component";
import {ContactComponent} from "./pages/contact/contact.component";
import {RecipeComponent} from "./pages/recipe/recipe.component";

const routes: Routes = [
  {path: '', component: IndexComponent},
  {path: 'start', redirectTo: '', pathMatch: 'full'},
  {path: 'recipe/:id', component: RecipeComponent},
  {path: 'contact', component: ContactComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
