import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

const JsonType = {
    fromAttribute: (attr) => { return JSON.parse(attr) },
    toAttribute: (prop) => { return JSON.stringify(prop) }
}

class GaestebuchNeuerKommentar extends LitElement {
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

        this.mitglieder = [];
        this.load = false;

        this.headline = "Neuer";
        this.change = false;

        this.mitglied = 1;
        this.kommentar = "";
        this.nr = 1;
        this.hidden = "";
    }

    async loadPHP() {
        gbNeuerKommentar[this.getAttribute("node")] = this;

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

    async saveKommentar() {
        this.hidden = html`<img src="image/load.gif" border="0" width="20">`;
        this.node = this.getAttribute("node");

        var data = {};
        data['mitgliedNodeID'] = this.shadowRoot.querySelector("#gb-neuer-kommentar-mitglied").value;
        data['kommentar'] = this.shadowRoot.querySelector("#gb-neuer-kommentar-kommentar").value;
        data['node'] = this.node;
        data['nr'] = this.shadowRoot.querySelector("#gb-neuer-kommentar-nr").value;

        await fetch(new Request('http://10.20.110.43/team21/view/createKommentar.php'), {
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

    async chsngeKommentar(ID) {
        await fetch(new Request('http://10.20.110.43/team21/view/getKommentar.php?ID='+ID), {
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
                this.headline = "Bearbeite";
                this.change = true;

                this.shadowRoot.querySelector("#gb-neuer-kommentar-mitglied").value = beitraege["mitglied"]["nodeID"];
                this.shadowRoot.querySelector("#gb-neuer-kommentar-nr").value = beitraege["kommentar"]["nr"];
                this.shadowRoot.querySelector("#gb-neuer-kommentar-kommentar").value = beitraege["kommentar"]["kommentar"];

                this.nodeID = beitraege["kommentar"]["nodeID"];
            });
    }

    async updateKommentar() {
        this.hidden = html`<img src="image/load.gif" border="0" width="20">`;

        var data = {};
        data['nodeID'] = this.nodeID;
        data['mitgliedNodeID'] = this.shadowRoot.querySelector("#gb-neuer-kommentar-mitglied").value;
        data['nr'] = this.shadowRoot.querySelector("#gb-neuer-kommentar-nr").value;
        data['kommentar'] = this.shadowRoot.querySelector("#gb-neuer-kommentar-kommentar").value;

        await fetch(new Request('http://10.20.110.43/team21/view/updateKommentar.php'), {
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
        <br><br>
        
        <h3>${this.headline} Kommentar</h3>
        <div style="margin-left: 20px;">
        
            <b>
            
            <select id="gb-neuer-kommentar-mitglied">
                ${options}
            </select>
            
            :</b><br><br>
            
            Nr.: <input type="number" id="gb-neuer-kommentar-nr" value="1">
            
            <br><br>
            
            <textarea id="gb-neuer-kommentar-kommentar" style="width: 90%;" rows="4"></textarea><br><br>
            
            <button @click="${this.change?this.updateKommentar:this.saveKommentar}">Abschicken</button>
            ${this.hidden}

        </div>
        <hr>
        `;
    }
}
customElements.define("gb-neuer-kommentar", GaestebuchNeuerKommentar);