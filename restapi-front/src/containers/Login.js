import React, { Component } from "react";
import { Button, FormGroup, FormControl, ControlLabel } from "react-bootstrap";
import "./Login.css";

export default class Login extends Component {
  constructor(props) {
    super(props);

    this.state = {
      login: "",
      password: "",
      api_key: "",
    };
  }

  validateFormApi() {
    return this.state.api_key.length > 12;
  }

  validateForm() {
    return this.state.login.length > 0 && this.state.password.length > 0;
  }


  handleChange = event => {
    this.setState({
      [event.target.id]: event.target.value
    });
  }


  handleSubmit = event => {
    event.preventDefault();
  }

  handleSubmitApi = event => {
    event.preventDefault();
  }

  render() {
    return (
      <div className="Login">
        <form onSubmit={this.handleSubmit}>
          <FormGroup controlId="login" bsSize="large">
            <ControlLabel>Login</ControlLabel>
            <FormControl
              autoFocus
              type="text"
              value={this.state.login}
              onChange={this.handleChange}
            />
          </FormGroup>
          <FormGroup controlId="password" bsSize="large">
            <ControlLabel>Password</ControlLabel>
            <FormControl
              value={this.state.password}
              onChange={this.handleChange}
              type="password"
            />
          </FormGroup>
          <Button
            block
            bsSize="large"
            disabled={!this.validateForm()}
            type="submit"
          >
            Login
          </Button>
        </form>
        <form onSubmit={this.handleSubmitApi}>
          <h1>OR</h1>
          <FormGroup controlId="api_key" bsSize="large">
            <ControlLabel>Api Key</ControlLabel>
            <FormControl
              autoFocus
              type="text"
              value={this.state.api_key}
              onChange={this.handleChange}
            />
          </FormGroup>
          <Button
            block
            bsSize="large"
            disabled={!this.validateFormApi()}
            type="submit"
          >
            Login
          </Button>
        </form>
      </div>
    );
  }
}