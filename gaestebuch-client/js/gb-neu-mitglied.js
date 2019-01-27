import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

class GaestebuchNeuesMitglied extends LitElement {
    static get properties() {
        return {
            hidden: {type: String},
            change: {type: Boolean}
        };
    }

    constructor() {
        super();

        gbNeuesMitglied = this;

        this.name = "";
        this.rolle = "M";
        this.geburtsdatum = "";
        this.geschlecht = "M";

        this.headline = "Neues";
        this.change = false;

        this.hidden = "";
    }

    async saveMitglied() {
        this.hidden = html`<img src="image/load.gif" border="0" width="20">`;

        var data = {};
        data['name'] = this.shadowRoot.querySelector("#gb-neues-mitglied-name").value;
        data['rolle'] = this.shadowRoot.querySelector("#gb-neues-mitglied-rolle").value;
        data['geburtsdatum'] = this.shadowRoot.querySelector("#gb-neues-mitglied-geburtsdatum").value;
        data['geschlecht'] = this.shadowRoot.querySelector("#gb-neues-mitglied-geschlecht").value;

        await fetch(new Request('http://10.20.110.43/team21/view/createMitglied.php'), {
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

    async changeMitglied(nodeID) {
        await fetch(new Request('http://10.20.110.43/team21/view/getMitglied.php?nodeID='+nodeID), {
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
            .then((mitglied) => {
                this.headline = "Bearbeite";
                this.change = true;

                this.shadowRoot.querySelector("#gb-neues-mitglied-name").value = mitglied["name"];
                this.shadowRoot.querySelector("#gb-neues-mitglied-rolle").value = mitglied["rolle"];
                this.shadowRoot.querySelector("#gb-neues-mitglied-geburtsdatum").value = mitglied["geburtsdatum"];
                this.shadowRoot.querySelector("#gb-neues-mitglied-geschlecht").value = mitglied["geschlecht"];

                this.nodeID = mitglied["nodeID"];
            });
    }

    async updateMitglied() {
        this.hidden = html`<img src="image/load.gif" border="0" width="20">`;

        var data = {};
        data['nodeID'] = this.nodeID;
        data['name'] = this.shadowRoot.querySelector("#gb-neues-mitglied-name").value;
        data['rolle'] = this.shadowRoot.querySelector("#gb-neues-mitglied-rolle").value;
        data['geburtsdatum'] = this.shadowRoot.querySelector("#gb-neues-mitglied-geburtsdatum").value;
        data['geschlecht'] = this.shadowRoot.querySelector("#gb-neues-mitglied-geschlecht").value;

        await fetch(new Request('http://10.20.110.43/team21/view/updateMitglied.php'), {
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
        return html`
        
            <h2>${this.headline} Mitglied</h2>

            Name des Mitglieds:<br>
            <input type="text" id="gb-neues-mitglied-name" style="width: 90%;"><br><br>
            
            Rolle des Mitglieds:<br>
            <select id="gb-neues-mitglied-rolle">
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="U">U</option>
                <option value="A">A</option>
            </select><br><br>
            
            Geburtsdatum des Mitglieds:<br>
            <input type="date" id="gb-neues-mitglied-geburtsdatum" style="width: 90%;" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"><br><br>
            
            Geschlecht des Mitglieds:<br>
            <select id="gb-neues-mitglied-geschlecht">
                <option value="m">M</option>
                <option value="w">W</option>
            </select><br><br>
            
            <button @click="${this.change?this.updateMitglied:this.saveMitglied}">Abschicken</button>
            ${this.hidden}
        
            <hr>
        
        `;
    }
}
customElements.define("gb-neues-mitglied", GaestebuchNeuesMitglied);