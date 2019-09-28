import React, { Component } from "react";
import LinkButton from './LinkButton';
import JustLink from './JustLink';
import BreadCrumbs from './BreadCrumbs';
import "./TableOne.css";

export default class TableOne extends React.Component {
    
    constructor(props){
      super(props);
      this.getUserInfo = this.getUserInfo.bind(this);
      this.getUserProducts = this.getUserProducts.bind(this);
      this.getUserName = this.getUserName.bind(this);
      this.path = [
        {name: 'Home', to: '/'}, 
        {name: 'Customers', to: '/customers'}, 
        {name: 'Current user'}
      ];
      this.getPath = this.getPath.bind(this);
    };


    getUserName = function(){
      return this.props.data.first_name;
    }

    getPath = function(){
      return this.path;
    }
    getUserInfo = function(){
      let user = this.props.data;
      let headers = this.props.headers;
      return <table class="table table-striped"><RenderUser user={user} headers={headers}/></table>;
    };
    
    getUserProducts = function(){
      return "";
    };

    render() {
        return (          
          <div className="col-md-12 mb-4">
            <BreadCrumbs path={this.getPath()} />
            <LinkButton to='/customers/'>Back to Customers</LinkButton><br/><br/>
            <div className="text-left">
              <div className="row TableOne">
                <div className="col col-md-6">
                  {this.getUserInfo()}
                </div>
                <div className="col col-md-6">
                  {this.getUserInfo()}
                </div>
              </div>
            </div>
          </div>
        );
    }
}


const RenderUser = (props) =>{
  return props.headers.map((value, index)=>{
    let title = value.title;
    let key = value.name;
    let val = props.user[key];
    return <tr><td><strong>{title}</strong></td><td>{val}</td></tr>;
  });
}