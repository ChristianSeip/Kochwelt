import { Component, OnInit } from '@angular/core';
import {ContactService} from "../../contact.service";
import {MatDialog} from "@angular/material/dialog";
import {DialogComponent} from "../../dialog/dialog.component";

@Component({
  selector: 'app-contact',
  templateUrl: './contact.component.html',
  styleUrls: ['./contact.component.scss']
})
export class ContactComponent implements OnInit {

  name: string = '';
  mail: string = '';
  message: string = '';

  constructor(private contactService: ContactService, public dialog: MatDialog) { }

  ngOnInit(): void {
  }

  /**
   * Check if form has a valid name.
   */
  isValidName(): boolean {
    if(this.name !== undefined) {
      this.name = this.name.trim();
      if(this.name.length >= 3 && this.name.length <= 30) {
        return true;
      }
    }
    return false;
  }

  /**
   * Check if form has a valid mail.
   */
  isValidMail(): boolean {
    if(this.mail !== undefined) {
      this.mail = this.mail.trim();
      if(this.mail.length <= 65) {
        if(this.mail.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
          return true;
        }
      }
    }
    return false;
  }

  /**
   * Check if form has a valid message.
   */
  isValidMessage(): boolean {
    if(this.message !== undefined) {
      this.message = this.message.trim();
      if(this.message.length >= 10 && this.message.length <= 1000) {
        return true;
      }
    }
    return false;
  }

  /**
   * Check if form is valid.
   */
  isValidForm(): boolean {
    return this.isValidName() && this.isValidMail() && this.isValidMessage();
  }

  /**
   * Send form and open dialog to show response.
   */
  sendForm(): void {
    // @ts-ignore
    this.contactService.addContact(this.name, this.mail, this.message).subscribe((response: any) => {
      if(response.success) {
        this.name = '';
        this.message = '';
        this.mail = '';
        response.msg = `${response.msg} Ticket-ID: ${response.id}`;
      }
      this.dialog.open(DialogComponent, {
        data: response
      });
    });
  }

}
