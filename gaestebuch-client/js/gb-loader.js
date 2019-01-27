import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

const JsonType = {
    fromAttribute: (attr) => { return JSON.parse(attr) },
    toAttribute: (prop) => { return JSON.stringify(prop) }
}

var loadLogo = document.querySelector("#loadLogo");

fetch(new Request('http://10.20.110.43/team21/view/getBeitraege.php'), {
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
    class GaestebuchLoader extends LitElement {
        static get properties() {
            return {
                load: {type: Number},
                beitraege: {type: JsonType},
                loadMore: {type: Boolean},
                mitglieder: {type: Boolean}
            };
        }

        constructor() {
            super();

            this.load = 5;
            this.loadMore = true;
            this.mitglieder = false;
            this.beitraege = [];
        }

        loadBeitraege() {
            for(var i = this.beitraege.length; i<this.load && i<beitraege.length; i++) {
                this.beitraege.push(beitraege[i]);
            }

            if(i >= beitraege.length) {
                this.loadMore = false;
            }
        }

        addBeitraege() {
            this.load += 5;
            this.loadBeitraege();
        }

        showMitglieder() {
            this.mitglieder = true;
        }

        render() {
            this.loadBeitraege();

            var button = html`<button @click="${this.addBeitraege}">Weitere Beiträge laden</button>`;
            if(!this.loadMore) {
                button = "";
            }

            loadLogo.hidden = true;

            return html`
                ${this.mitglieder==true?
                html`<gb-neues-mitglied></gb-neues-mitglied>
                <gb-loeschen-mitglied></gb-loeschen-mitglied>
                `:html`<button @click="${this.showMitglieder}">Mitglieder bearbeiten</button><hr>
                `}
                <gb-neuer-beitrag></gb-neuer-beitrag>
                <h2>Timeline</h2>
                ${this.beitraege.map((i) => html`<gb-beitrag id="${i}"></gb-beitrag>`)}
                
                ${button}
            `;
        }
    }
    customElements.define("gb-loader", GaestebuchLoader);
});