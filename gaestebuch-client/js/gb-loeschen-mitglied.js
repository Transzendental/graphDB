import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

const JsonType = {
    fromAttribute: (attr) => { return JSON.parse(attr) },
    toAttribute: (prop) => { return JSON.stringify(prop) }
}

class GaestebuchLoeschenMitglied extends LitElement {
    static get properties() {
        return {
            mitglieder: {type: JsonType},
            load: {type: Boolean}
        };
    }

    constructor() {
        super();

        this.mitglieder = [];
        this.load = false;
    }

    async loadPHP() {
        await fetch(new Request('http://10.20.110.43/team21/view/getMitglieder.php'), {
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
                beitraege.map((i) => {
                    this.mitglieder[i.nodeID] = i.name;
                });
                this.nodeID = this.mitglieder[0];
                this.load = true;
            });
    }

    async deleteMitglied() {
        this.nodeID = this.shadowRoot.querySelector("#gb-neuer-kommentar-mitglied").value;
        await fetch(new Request('http://10.20.110.43/team21/view/deleteMitglied.php?ID='+this.nodeID), {
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

    updateMitglied() {
        gbNeuesMitglied.changeMitglied(this.shadowRoot.querySelector("#gb-neuer-kommentar-mitglied").value);
    }

    render() {
        this.loadPHP();

        var options = [];
        this.mitglieder.forEach(function (v, k) {
            options.push(html`<option value="${k}">${v}</option>`);
        });

        return html`

            <h2>Mitglied bearbeiten/l&ouml;schen</h2>
            
            <select id="gb-neuer-kommentar-mitglied">
                ${options}
            </select>
            <button @click="${this.updateMitglied}">Mitglied bearbeiten</button> <button @click="${this.deleteMitglied}">Mitglied l&ouml;schen</button>
            <hr>
`;
    }
}
customElements.define("gb-loeschen-mitglied", GaestebuchLoeschenMitglied);