import React, { Component } from "react";
import LinkButton from './LinkButton';

export default class Table extends React.Component {
    
    constructor(props){
      super(props);
      this.getHeader = this.getHeader.bind(this);
      this.getRowsData = this.getRowsData.bind(this);
      this.getKeys = this.getKeys.bind(this);
    }
    
    getKeys = function(){
      if (this.props.data.length>0){
        return this.props.headers.map(x => x.name);
      }
      return []
    }
    
    getHeader = function(){
      return this.props.headers.map((key, index)=>{
        return <th key={key.name}>{key.title}</th>
      })
    }
    
    getRowsData = function(){
      var items = this.props.data;
      var keys = this.getKeys();      
      return items.map((row, index)=>{
        console.log(row, index);
        return <tr key={index}><RenderRow key={index} data={row} keys={keys}/></tr>
      })
    }
    
    render() {
        return (
          <div>
            <table class="table">
              <thead>
                <tr>{this.getHeader()}</tr>
            </thead>
            <tbody>
              {this.getRowsData()}
            </tbody>
            </table>
          </div>
          
        );
    }
}

const RenderRow = (props) =>{
  return props.keys.map((key, index)=>{
    let data = props.data[key];
    let id = props.data['id'];
    let viewLink = '/customers/' + id + '/';    
    let editLink = '/customers/' + id + '/edit';    
    if (typeof(data) === 'object'){
      data = data.length;
    }
    if (key === 'operation'){
      return (
              <td key={data}>
                <LinkButton to={viewLink}>view</LinkButton>&nbsp;
                <LinkButton to={editLink}>edit</LinkButton>
              </td>
      );
    }
    else{
      return <td key={data}>{data}</td>      
    }
  })
}