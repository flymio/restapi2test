import React, { Component } from "react";
import { Button, FormGroup, FormControl, ControlLabel } from "react-bootstrap";
import TableOne from "../components/TableOne";

import "./Customers.css";

export default class CustomerView extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {},
      headers: [
          {name:'id', title:'ID'},
          {name:'first_name', title:'First Name'},
          {name:'last_name', title:'Last Name'},
          {name:'date_of_birth', title:'BSD'},
          {name:'status', title:'Status'}
      ]
    };    

  }

  fetchOne() {
    var that = this;
    fetch('/fakedata/customer_one.json').then(function (response) {
      return response.json();
    }).then(function (result) {
      console.log(result);
      that.setState({ 'data': result});
    });
  }

  componentWillMount() {
      this.fetchOne();
  };
  
  render() {
    return (
      <div className="Customers">
        <TableOne data={this.state.data} headers={this.state.headers}/>
      </div>
    );
  }
}