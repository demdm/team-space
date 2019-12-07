
/**
 * @param object1
 * @param object2
 * @returns {boolean}
 */
export function areObjectsEqual(object1, object2) {
    if (typeof object1 !== 'object' || typeof object2 !== 'object') {
        new Error('Invalid parameter');
    }

    if (Object.keys(object1).length !== Object.keys(object2).length) {
        return false;
    }

    for (var objectKey in object1) {
        if (!object2.hasOwnProperty(objectKey)) {
            return false;
        }

        if (object1[objectKey] !== object2[objectKey]) {
            return false;
        }
    }

    return true;
}
