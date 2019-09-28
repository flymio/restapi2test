import React from 'react'
import PropTypes from 'prop-types'
import { withRouter } from 'react-router'

const JustLink = (props) => {
  const {
    history,
    location,
    match,
    staticContext,
    to,
    text,
    className,
    onClick,
    // ⬆ filtering out props that `button` doesn’t know what to do with.
    ...rest
  } = props
  return (
    <a class="{className}" href="javascript:void(0)"
      {...rest} // `children` is just another prop!
      onClick={(event) => {
        onClick && onClick(event)
        history.push(to)
      }}
    >{text}</a>
  )
}

JustLink.propTypes = {
  to: PropTypes.string.isRequired,
}

export default withRouter(JustLink)