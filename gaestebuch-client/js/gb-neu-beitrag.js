import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

const JsonType = {
    fromAttribute: (attr) => { return JSON.parse(attr) },
    toAttribute: (prop) => { return JSON.stringify(prop) }
}

class GaestebuchNeuerBeitrag extends LitElement {
    static get properties() {
        return {
            mitglieder: {type: JsonType},
            load: {type: Boolean},
            hidden: {type: String},
            change: {type: Boolean}
        };
    }

    constructor() {
        super();

        gbNeuerBeitrag = this;

        this.mitglieder = [];
        this.load = false;

        this.headline = "erstellen";
        this.change = false;

        this.mitglied = 1;
        this.beitrag = "";
        this.hidden = "";
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
                this.load = true;
            });
    }

    async saveBeitrag() {
        this.hidden = html`<img src="image/load.gif" border="0" width="20">`;
        var data = {};
        data['mitgliedNodeID'] = this.shadowRoot.querySelector("#gb-neuer-beitrag-mitglied").value;
        data['beitrag'] = this.shadowRoot.querySelector("#gb-neuer-beitrag-beitrag").value;

        await fetch(new Request('http://10.20.110.43/team21/view/createBeitrag.php'), {
            method: 'POST',
            mode: 'cors',
            cache: 'no-store',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                location.reload(true);
            });
    }

    async changeBeitrag(ID) {
        await fetch(new Request('http://10.20.110.43/team21/view/getBeitrag.php?ID='+ID), {
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
                this.headline = "bearbeiten";
                this.change = true;

                this.shadowRoot.querySelector("#gb-neuer-beitrag-mitglied").value = beitraege["mitglied"]["nodeID"];
                this.shadowRoot.querySelector("#gb-neuer-beitrag-beitrag").value = beitraege["beitrag"]["beitrag"];

                this.nodeID = beitraege["beitrag"]["nodeID"];
            });
    }

    async updateBeitrag() {
        this.hidden = html`<img src="image/load.gif" border="0" width="20">`;

        var data = {};
        data['nodeID'] = this.nodeID;
        data['mitgliedNodeID'] = this.shadowRoot.querySelector("#gb-neuer-beitrag-mitglied").value;
        data['beitrag'] = this.shadowRoot.querySelector("#gb-neuer-beitrag-beitrag").value;

        await fetch(new Request('http://10.20.110.43/team21/view/updateBeitrag.php'), {
            method: 'POST',
            mode: 'cors',
            cache: 'no-store',
            body: JSON.stringify(data),
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

        var options = [];

        this.mitglieder.forEach(function (v, k) {
            options.push(html`<option value="${k}">${v}</option>`);
        });

        return html`
        
            <h2>Beitrag ${this.headline}</h2>
        
            <b>
            
            <select id="gb-neuer-beitrag-mitglied">
                ${options}
            </select>
            
            :</b><br><br>
            
            <textarea id="gb-neuer-beitrag-beitrag" style="width: 90%;" rows="4"></textarea><br><br>
            
            <button @click="${this.change?this.updateBeitrag:this.saveBeitrag}">Abschicken</button>
            ${this.hidden}
            <hr>
        
        `;
    }
}
customElements.define("gb-neuer-beitrag", GaestebuchNeuerBeitrag);