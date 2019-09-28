import React from 'react'
import PropTypes from 'prop-types'
import { withRouter } from 'react-router'
import JustLink from './JustLink';

const BreadCrumbs = (props) => {
  const {
    history,
    location,
    match,
    staticContext,
    to,
    path,
    onClick,
    // ⬆ filtering out props that `button` doesn’t know what to do with.
    ...rest
  } = props


  let breadcrumbs = path.map((item, key) =>    
    <li class="breadcrumb-item"><JustLink to={item.to} text={item.name} /></li>
  );


  return (
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        {breadcrumbs}
      </ol>
    </nav>
  );


}


export default withRouter(BreadCrumbs)

