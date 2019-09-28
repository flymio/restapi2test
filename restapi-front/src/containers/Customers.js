import React, { Component } from "react";
import { Button, FormGroup, FormControl, ControlLabel } from "react-bootstrap";
import Table from "../components/Table";

import "./Customers.css";

export default class Customers extends Component {
  constructor(props) {
    super(props);    
    this.state = {
      data: [],
      headers: [
          {name:'id', title:'ID'},
          {name:'first_name', title:'First Name'},
          {name:'last_name', title:'Last Name'},
          {name:'date_of_birth', title:'BSD'},
          {name:'products', title:'Products'},
          {name:'status', title:'Status'},
          {name:'operation', title:'Operation'},
      ]
    };
  }

  fetchList() {
    var that = this;
    fetch('/fakedata/customers.json').then(function (response) {
      return response.json();
    }).then(function (result) {
      console.log(result);
      that.setState({ 'data': result});        
    });
  }

  componentWillMount() {
      this.fetchList();
  };
  
  render() {
    return (
      <div className="Customers">
        <Table data={this.state.data} headers={this.state.headers}/>
      </div>
    );
  }
}