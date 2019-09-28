import React from "react";
import { Route, Switch } from "react-router-dom";
import Home from "./containers/Home";
import NotFound from "./containers/404";
import Login from "./containers/Login";
import Customers from "./containers/Customers";
import CustomerView from "./containers/CustomerView";


export default () =>
  <Switch>
    <Route path="/" exact component={Home} />
    <Route path="/login" exact component={Login} />
    <Route path="/customers" exact component={Customers}  />
    <Route path="/customers/:id" exact component={CustomerView}  />
    <Route path="/customers/:id/edit" exact component={CustomerView}  />
    <Route component={NotFound} />
  </Switch>;