const camelize = (str) =>
    str.split(' ').map((word) =>
        (word.charAt(0).toUpperCase() + word.slice(1))
    ).join('');

export default camelize;