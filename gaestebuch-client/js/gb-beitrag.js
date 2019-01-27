import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

class GaestebuchBeitrag extends LitElement {
    static get properties() {
        return {
            mitglied: {type: String},
            beitrag: {type: String},
            kommentareCount: {type: Number},
            kommentareShow: {type: Boolean}
        };
    }

    constructor() {
        super();
        this.kommentare = [];
        this.kommentareShow = false;
    }

    async loadPHP() {
        await fetch(new Request('http://10.20.110.43/team21/view/getBeitrag.php?ID='+this.id), {
            method: 'GET',
            mode: 'cors',
            cache: 'no-store',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                return response.json();
            })
            .then((beitraege) => {
                this.beitrag = beitraege["beitrag"]["beitrag"];
                this.nodeID = beitraege["beitrag"]["nodeID"];
                this.mitglied = beitraege["mitglied"]["name"];
                this.mitgliedID = beitraege["mitglied"]["ID"];
                this.kommentare = beitraege["kommentare"];
                this.kommentareCount = this.kommentare.length;
            });
    }

    showKommentare() {
        this.kommentareShow = true;
    }

    updateBeitrag() {
        gbNeuerBeitrag.changeBeitrag(this.id);
    }

    async deleteBeitrag() {
        await fetch(new Request('http://10.20.110.43/team21/view/deleteBeitrag.php?ID='+this.nodeID), {
            method: 'GET',
            mode: 'cors',
            cache: 'no-store',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                location.reload(true);
            });
    }

    render() {
        this.loadPHP();

        var kommentareBox;
        if(!this.kommentareShow) {
            kommentareBox = html`<button @click="${this.showKommentare}">Kommentare anzeigen (${this.kommentareCount})</button>`;
        } else {
            kommentareBox = html`${this.kommentare.map((i) => html`<gb-kommentar id="${i}"></gb-kommentar>`)}<gb-neuer-kommentar node="${this.nodeID}"></gb-neuer-kommentar>`;
        }

        return html`<b>${this.mitglied==null?html`<img src="image/load.gif" border="0" width="20">`:html`<a href="mitglied.php?id=${this.mitgliedID}">${this.mitglied}</a>`}:</b> ${this.beitrag}<br>
        <button @click="${this.updateBeitrag}">Beitrag bearbeiten</button> <button @click="${this.deleteBeitrag}">Beitrag l&ouml;schen</button> ${kommentareBox}
        <hr>`;
    }
}
customElements.define("gb-beitrag", GaestebuchBeitrag);